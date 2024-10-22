<?php

namespace App\Http\Controllers\API;

use App\Models\Invoice;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use App\Http\Resources\Invoice\InvoiceResource;
use Knuckles\Scribe\Attributes\ResponseFromFile;
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
            ->when($request->customer_id,
                static fn ($query, $customerId) => $query
                    ->where('customer_id', '=', $customerId)
            )
            ->simplePaginate($this->clamp($request->input('per_page'), 25, 100));

        return InvoiceResource::collection($invoices);
    }

    /**
     * Create Invoice
     */
    public function store(Request $request)
    {
        //
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
     */
    public function update(Request $request, string $id)
    {
        //
    }
}
