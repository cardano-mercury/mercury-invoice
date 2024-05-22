<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Enums\Status;
use Inertia\Response;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Service;
use App\Models\Customer;
use App\Traits\HashIdTrait;
use App\Models\InvoiceItem;
use App\Models\InvoiceActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use App\Jobs\SendNewInvoiceNotificationMailJob;
use App\Http\Requests\Invoice\StoreInvoiceRequest;

class InvoiceController extends Controller
{
    use HashIdTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $invoices = Invoice::query()
            ->where('user_id', auth()->id())
            ->with(['customer', 'items'])
            ->orderBy('id', 'desc')
            ->get();

        return Inertia::render('Invoice/Index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $customers = Customer::query()
            ->where('user_id', auth()->id())
            ->select(['id', 'name', 'tax_number', 'tax_rate'])
            ->with([
                'emails' => static function ($query) {
                    $query
                        ->orderBy('id', 'desc')
                        ->select('id', 'customer_id', 'name', 'address');
                },
                'addresses' => static function ($query) {
                    $query
                        ->orderBy('id', 'desc')
                        ->select('id', 'customer_id', 'name', 'line1', 'line2', 'city', 'state', 'postal_code', 'country');
                }
            ])
            ->orderBy('id', 'desc')
            ->get();

        $products = Product::query()
            ->where('user_id', auth()->id())
            ->select(['id', 'sku', 'name', 'description', 'unit_price'])
            ->orderBy('id', 'desc')
            ->get();

        $services = Service::query()
            ->where('user_id', auth()->id())
            ->select(['id', 'name', 'description', 'unit_price'])
            ->orderBy('id', 'desc')
            ->get();

        return Inertia::render(
            'Invoice/Create',
            compact(
                'customers',
                'products',
                'services',
            ),
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        if ($request->validated('save_mode') === 'Draft') {
            $status = Status::DRAFT;
        } else {
            $status = Status::PUBLISHED;
        }

        $invoiceData = [
            'user_id' => auth()->id(),
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

            $invoiceItemData = $request->validated('items');
            foreach ($invoiceItemData as $index => $item) {
                $invoiceItemData[$index]['invoice_id'] = $invoice->id;
            }
            InvoiceItem::insert($invoiceItemData);

            InvoiceActivity::create([
                'invoice_id' => $invoice->id,
                'activity' => sprintf(
                    'New invoice created in %s mode.',
                    $status->value,
                ),
            ]);

        });

        if ($status === Status::PUBLISHED) {
            dispatch(new SendNewInvoiceNotificationMailJob($invoice));
        }

        session()->flash('success', sprintf('Invoice record created in %s mode', $status->value));

        return to_route('invoices.show', $this->encodeId($invoice->id));
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice): Response
    {
        $invoice->load([
            'customer',
            'billingAddress',
            'shippingAddress',
            'recipients',
            'activities' => static function ($query) {
                $query->orderBy('id', 'desc');
            },
            'payments' => static function ($query) {
                $query->orderBy('id', 'desc');
            },
        ]);

        return Inertia::render('Invoice/Show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice): Response | RedirectResponse
    {
        if ($invoice->status !== Status::DRAFT) {
            session()->flash('error', 'Only draft invoices can be edited.');
            return to_route('invoices.show', $this->encodeId($invoice->id));
        }

        $invoice->load([
            'customer',
            'billingAddress',
            'shippingAddress',
            'recipients',
        ]);

        $customers = Customer::query()
            ->where('user_id', auth()->id())
            ->select(['id', 'name', 'tax_number', 'tax_rate'])
            ->with([
                'emails' => static function ($query) {
                    $query
                        ->orderBy('id', 'desc')
                        ->select('id', 'customer_id', 'name', 'address');
                },
                'addresses' => static function ($query) {
                    $query
                        ->orderBy('id', 'desc')
                        ->select('id', 'customer_id', 'name', 'line1', 'line2', 'city', 'state', 'postal_code', 'country');
                }
            ])
            ->orderBy('id', 'desc')
            ->get();

        $products = Product::query()
            ->where('user_id', auth()->id())
            ->select(['id', 'sku', 'name', 'description', 'unit_price'])
            ->orderBy('id', 'desc')
            ->get();

        $services = Service::query()
            ->where('user_id', auth()->id())
            ->select(['id', 'name', 'description', 'unit_price'])
            ->orderBy('id', 'desc')
            ->get();

        return Inertia::render(
            'Invoice/Edit',
            compact(
                'invoice',
                'customers',
                'products',
                'services',
            ),
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
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

            $invoiceItemData = $request->validated('items');
            foreach ($invoiceItemData as $index => $item) {
                $invoiceItemData[$index]['invoice_id'] = $invoice->id;
            }
            InvoiceItem::insert($invoiceItemData);

            InvoiceActivity::create([
                'invoice_id' => $invoice->id,
                'activity' => sprintf(
                    'Invoice updated in %s mode.',
                    $status->value,
                ),
            ]);

        });

        if ($status === Status::PUBLISHED) {
            dispatch(new SendNewInvoiceNotificationMailJob($invoice));
        }

        session()->flash('success', sprintf('Invoice record updated in %s mode', $status->value));

        return to_route('invoices.show', $this->encodeId($invoice->id));
    }

    /**
     * Voids the specified resource in storage.
     */
    public function void(Invoice $invoice): RedirectResponse
    {
        if ($invoice->status === Status::PAID) {
            session()->flash('error', 'Cannot void a paid invoice.');
        } else {

            $invoice->update(['status' => Status::VOIDED]);

            InvoiceActivity::create([
                'invoice_id' => $invoice->id,
                'activity' => 'Invoice was voided.',
            ]);

            session()->flash('success', 'Invoice successfully voided.');

        }

        return to_route('invoices.show', $this->encodeId($invoice->id));
    }

    /**
     * Restores the specified resource in storage.
     */
    public function restore(Invoice $invoice): RedirectResponse
    {
        if ($invoice->status === Status::VOIDED) {

            $invoice->update(['status' => Status::DRAFT]);

            InvoiceActivity::create([
                'invoice_id' => $invoice->id,
                'activity' => 'Invoice was restored.',
            ]);

            session()->flash('success', 'Invoice successfully restored.');

        } else {
            session()->flash('error', 'Only voided invoices can be restored.');
        }

        return to_route('invoices.edit', $this->encodeId($invoice->id));
    }

    public function sendReminderNotifications(Invoice $invoice): RedirectResponse
    {
        if ($invoice->status === Status::PUBLISHED) {
            if (!$invoice->last_notified || $invoice->last_notified->diffInHours() >= 24) {

                dispatch(new SendNewInvoiceNotificationMailJob($invoice, true));

                InvoiceActivity::create([
                    'invoice_id' => $invoice->id,
                    'activity' => 'Reminder notifications were manually sent.',
                ]);

                session()->flash('success', 'Reminder notification(s) were successfully sent.');

            } else {
                session()->flash('error', 'Reminder can only be sent once a day; notification(s) were last sent ' . $invoice->last_notified->diffForHumans() . '.');
            }
        } else {
            session()->flash('error', 'Reminder notification can only be sent to published invoices.');
        }

        return to_route('invoices.show', $this->encodeId($invoice->id));
    }

    public function manuallyMarkAsPaid(Invoice $invoice): RedirectResponse
    {
        if ($invoice->status === Status::PUBLISHED) {

            $invoice->update(['status' => Status::PAID]);

            InvoiceActivity::create([
                'invoice_id' => $invoice->id,
                'activity' => 'Invoice was manually marked as paid.',
            ]);

            // TODO: Things to consider
            //       Do we need to create payment record, when invoice is manually marked as paid?
            //       Do we need to notify the invoice recipients that the invoice was manually marked as paid?

            session()->flash('success', 'Invoice successfully marked as paid.');

        } else {
            session()->flash('error', 'Only published invoices can be manually marked as paid.');
        }

        return to_route('invoices.show', $this->encodeId($invoice->id));
    }
}
