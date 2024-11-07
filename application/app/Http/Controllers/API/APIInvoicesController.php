<?php

namespace App\Http\Controllers\API;

use App\Enums\Status;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\InvoiceActivity;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use App\Jobs\SendNewInvoiceNotificationMailJob;
use App\Http\Resources\Invoice\InvoiceResource;
use Knuckles\Scribe\Attributes\ResponseFromFile;
use App\Http\Requests\Invoice\StoreInvoiceRequest;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

#[Group('Invoices', 'Invoice Management API')]
#[ResponseFromFile(file: 'resources/api-responses/401.json', status: 401, description: 'Unauthorized')]
#[ResponseFromFile(file: 'resources/api-responses/404.json', status: 404, description: 'Not Found')]
#[ResponseFromFile(file: 'resources/api-responses/400.json', status: 404, description: 'Bad Request')]
#[ResponseFromFile(file: 'resources/api-responses/500.json', status: 500, description: 'Internal Server Error')]
class APIInvoicesController extends Controller
{
    use HelperTrait;

    /**
     * List Invoices
     */
    #[ResponseFromApiResource(InvoiceResource::class, Invoice::class, status: 200, description: 'OK', collection: true, simplePaginate: 25)]
    #[QueryParam('customer_id', 'integer', description: 'Filter invoices by customer id', required: false, example: 1234)]
    #[QueryParam('per_page', 'integer', description: 'Number of results per page (Min 25, Max 100)', required: false, example: 25)]
    #[QueryParam('page', 'integer', description: 'Page number', required: false, example: 1)]
    public function index(Request $request): JsonResponse|AnonymousResourceCollection
    {
        if (!$request->user()->tokenCan('Invoices:Read')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $invoices = Invoice::query()
            ->where('user_id', $request->user()->id)
            ->with(['items'])
            ->when($request->customer_id,
                static fn ($query, $customerId) => $query
                    ->where('customer_id', '=', $customerId)
            )
            ->simplePaginate($this->clamp($request->input('per_page'), 25, 100));

        return InvoiceResource::collection($invoices);
    }

    /**
     * Create Invoice
     *
     * When invoices are created in <strong>Published</strong> status,
     * the invoice will go live and no further changes can be made and
     * email notifications will be sent to the specified <code>customer_email_ids</code> in the request body.
     */
    #[ResponseFromApiResource(InvoiceResource::class, Invoice::class, status: 201, description: 'Created')]
    #[ResponseFromFile(file: 'resources/api-responses/422.json', status: 422, description: 'Validation Failed')]
    public function store(StoreInvoiceRequest $request): InvoiceResource|JsonResponse
    {
        if (!$request->user()->tokenCan('Invoices:Create')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($request->validated('save_mode') === 'Draft') {
            $status = Status::DRAFT;
        } else {
            $status = Status::PUBLISHED;
        }

        $invoiceData = [
            'user_id' => auth()->id(),
            'currency' => auth()->user()->account_currency,
            ...collect($request->validated())->only([
                'customer_id',
                'billing_address_id',
                'shipping_address_id',
                'customer_reference',
                'issue_date',
                'due_date'
            ])->toArray(),
            'status' => $status,
        ];

        $invoice = null;

        DB::transaction(function () use (&$invoice, $invoiceData, $request, $status) {

            /** @var Invoice $invoice */
            $invoice = Invoice::create($invoiceData);

            $invoice->recipients()->sync($request->validated('customer_email_ids'));

            $invoiceTotal = 0;
            $invoiceItemData = $request->validated('items');
            foreach ($invoiceItemData as $index => $item) {
                $invoiceItemData[$index]['invoice_id'] = $invoice->id;
                $subtotal = (float) $item['quantity'] * (float) $item['unit_price'];
                $tax = $subtotal * ((float) $item['tax_rate'] / 100);
                $invoiceTotal += ($subtotal + $tax);
            }
            InvoiceItem::insert($invoiceItemData);

            $invoice->update(['total' => $invoiceTotal]);

            InvoiceActivity::create([
                'invoice_id' => $invoice->id,
                'activity' => sprintf(
                    'New invoice created in %s mode via API.',
                    $status->value,
                ),
            ]);

        });

        if ($status === Status::PUBLISHED) {
            dispatch(new SendNewInvoiceNotificationMailJob($invoice));
        }

        $invoice->load([
            'customer',
            'items',
            'billingAddress',
            'shippingAddress',
            'recipients',
            'payments',
            'activities',
        ]);

        return new InvoiceResource($invoice);
    }

    /**
     * Get Invoice
     */
    #[ResponseFromApiResource(InvoiceResource::class, Invoice::class, status: 200, description: 'OK')]
    public function show(Request $request, Invoice $invoice): InvoiceResource|JsonResponse
    {
        if (!$request->user()->tokenCan('Invoices:Read')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $invoice->load([
            'customer',
            'items',
            'billingAddress',
            'shippingAddress',
            'recipients',
            'payments',
            'activities',
        ]);

        return new InvoiceResource($invoice);
    }

    /**
     * Update Invoice
     *
     * When invoices are updated to <strong>Published</strong> status,
     * the invoice will go live and no further changes can be made and
     * email notifications will be sent to the specified <code>customer_email_ids</code> in the request body.
     */
    #[ResponseFromApiResource(InvoiceResource::class, Invoice::class, status: 200, description: 'OK')]
    #[ResponseFromFile(file: 'resources/api-responses/422.json', status: 422, description: 'Validation Failed')]
    public function update(StoreInvoiceRequest $request, Invoice $invoice): InvoiceResource|JsonResponse
    {
        if (!$request->user()->tokenCan('Invoices:Update')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($invoice->status !== Status::DRAFT) {
            return response()->json(['message' => 'Only draft invoice can be updated'], 400);
        }

        if ($request->validated('save_mode') === 'Draft') {
            $status = Status::DRAFT;
        } else {
            $status = Status::PUBLISHED;
        }

        $invoiceData = [
            ...collect($request->validated())->only([
                'customer_id',
                'billing_address_id',
                'shipping_address_id',
                'customer_reference',
                'issue_date',
                'due_date'
            ])->toArray(),
            'status' => $status,
        ];

        DB::transaction(static function () use ($invoice, $invoiceData, $request, $status) {

            $invoice->update($invoiceData);

            $invoice->recipients()->sync($request->validated('customer_email_ids'));

            InvoiceItem::query()->where('invoice_id', $invoice->id)->delete();

            $invoiceTotal = 0;
            $invoiceItemData = $request->validated('items');
            foreach ($invoiceItemData as $index => $item) {
                $invoiceItemData[$index]['invoice_id'] = $invoice->id;
                $subtotal = (float) $item['quantity'] * (float) $item['unit_price'];
                $tax = $subtotal * ((float) $item['tax_rate'] / 100);
                $invoiceTotal += ($subtotal + $tax);
            }
            InvoiceItem::insert($invoiceItemData);

            $invoice->update(['total' => $invoiceTotal]);

            InvoiceActivity::create([
                'invoice_id' => $invoice->id,
                'activity' => sprintf(
                    'Invoice updated in %s mode via API.',
                    $status->value,
                ),
            ]);

        });

        if ($status === Status::PUBLISHED) {
            dispatch(new SendNewInvoiceNotificationMailJob($invoice));
        }

        $invoice->load([
            'customer',
            'items',
            'billingAddress',
            'shippingAddress',
            'recipients',
            'payments',
            'activities',
        ]);

        return new InvoiceResource($invoice);
    }

    /**
     * Void Invoice
     *
     * Marks the invoice as <strong>Voided</strong> status, so the customer will not be expected to pay the invoice.
     */
    #[\Knuckles\Scribe\Attributes\Response(status: 204, description: 'Invoice Voided')]
    public function void(Request $request, Invoice $invoice): Response|JsonResponse
    {
        if (!$request->user()->tokenCan('Invoices:Update')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($invoice->status === Status::PAID) {
            return response()->json(['message' => 'Cannot void a paid invoice'], 400);
        }

        if ($invoice->status === Status::VOIDED) {
            return response()->json(['message' => 'Invoice is already voided'], 400);
        }

        $invoice->update(['status' => Status::VOIDED]);

        InvoiceActivity::create([
            'invoice_id' => $invoice->id,
            'activity' => 'Invoice was voided via API.',
        ]);

        return response()->noContent();
    }

    /**
     * Restore Invoice
     *
     * Restores <strong>Voided</strong> invoice back into <strong>Draft</strong> status, so that it can be further updated.
     */
    #[\Knuckles\Scribe\Attributes\Response(status: 204, description: 'Invoice Restored')]
    public function restore(Request $request, Invoice $invoice): Response|JsonResponse
    {
        if (!$request->user()->tokenCan('Invoices:Update')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($invoice->status !== Status::VOIDED) {
            return response()->json(['message' => 'Only voided invoice can be restored'], 400);
        }

        $invoice->update(['status' => Status::DRAFT]);

        InvoiceActivity::create([
            'invoice_id' => $invoice->id,
            'activity' => 'Invoice was restored via API.',
        ]);

        return response()->noContent();
    }

    /**
     * Send Invoice Reminder Notifications
     *
     * Sends invoice reminders to all recipients attached to the invoice, can only be used once a day on published invoices.
     */
    #[\Knuckles\Scribe\Attributes\Response(status: 204, description: 'Invoice Reminder Notifications Sent')]
    public function sendReminderNotifications(Request $request, Invoice $invoice): Response|JsonResponse
    {
        if (!$request->user()->tokenCan('Invoices:Update')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($invoice->status !== Status::PUBLISHED) {
            return response()->json(['message' => 'Reminder notification can only be sent to published invoices'], 400);
        }

        if ($invoice->last_notified && $invoice->last_notified->diffInHours() <= 24) {
            return response()->json(['message' => 'Reminder can only be sent once a day; notification(s) were last sent ' . $invoice->last_notified->diffForHumans() . '.'], 400);
        }

        dispatch(new SendNewInvoiceNotificationMailJob($invoice, true));

        InvoiceActivity::create([
            'invoice_id' => $invoice->id,
            'activity' => 'Reminder notifications were manually sent via API.',
        ]);

        return response()->noContent();
    }

    /**
     * Manually Mark Invoice As Paid
     *
     * Updates the invoice status to <strong>Paid</strong> manually, without processing any form of payments.
     */
    #[\Knuckles\Scribe\Attributes\Response(status: 204, description: 'Invoice Reminder Notifications Sent')]
    public function manuallyMarkAsPaid(Request $request, Invoice $invoice): Response|JsonResponse
    {
        if (!$request->user()->tokenCan('Invoices:Update')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($invoice->status !== Status::PUBLISHED) {
            return response()->json(['message' => 'Only published invoices can be manually marked as paid'], 400);
        }

        $invoice->update(['status' => Status::PAID]);

        InvoiceActivity::create([
            'invoice_id' => $invoice->id,
            'activity' => 'Invoice was manually marked as paid via API.',
        ]);

        // TODO: Things to consider
        //       Do we need to create payment record, when invoice is manually marked as paid?
        //       Do we need to notify the invoice recipients that the invoice was manually marked as paid?

        return response()->noContent();
    }
}
