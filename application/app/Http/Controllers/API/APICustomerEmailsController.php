<?php

namespace App\Http\Controllers\API;

use App\Models\Email;
use App\Models\Customer;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use App\Http\Resources\CustomerEmailResource;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use App\Http\Requests\Customer\StoreCustomerEmailRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

#[Group('Customer Emails', 'Customer Email Management API')]
class APICustomerEmailsController extends Controller
{
    use HelperTrait;

    /**
     * List Customer Emails
     */
    #[ResponseFromApiResource(CustomerEmailResource::class, Email::class, collection: true, simplePaginate: 25)]
    #[QueryParam('search', 'string', description: 'Search for customer emails by name or address', required: false, example: 'john.doe@example.com')]
    #[QueryParam('per_page', 'integer', description: 'Number of results per page (Min 25, Max 100)', required: false, example: 25)]
    #[QueryParam('page', 'integer', description: 'Page number', required: false, example: 1)]
    public function index(Request $request, Customer $customer): JsonResponse|AnonymousResourceCollection
    {
        if (!$request->user()->tokenCan('CustomerEmails:Read')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customerEmails = $customer->emails()
            ->when($request->search,
                static fn ($query, $search) => $query
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%')
            )
            ->simplePaginate($this->clamp($request->input('per_page'), 25, 100));

        return CustomerEmailResource::collection($customerEmails);
    }

    /**
     * Create Customer Email
     */
    #[ResponseFromApiResource(CustomerEmailResource::class, Email::class)]
    public function store(StoreCustomerEmailRequest $request, Customer $customer): JsonResponse|CustomerEmailResource
    {
        if (!$request->user()->tokenCan('CustomerEmails:Create')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $email = Email::create(array_merge(
            ['customer_id' => $customer->id],
            $request->validated()
        ));

        if ((bool) $request->validated('is_default') === true) {
            Email::query()
                ->where('customer_id', '=', $customer->id)
                ->where('id', '!=', $email->id)
                ->update(['is_default' => false]);
        }

        return new CustomerEmailResource($email);
    }

    /**
     * Get Customer Email
     */
    #[ResponseFromApiResource(CustomerEmailResource::class, Email::class)]
    public function show(Request $request, Customer $customer, Email $email): JsonResponse|CustomerEmailResource
    {
        if (!$request->user()->tokenCan('CustomerEmails:Read')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return new CustomerEmailResource($email);
    }

    /**
     * Update Customer Email
     */
    #[ResponseFromApiResource(CustomerEmailResource::class, Email::class)]
    public function update(StoreCustomerEmailRequest $request, Customer $customer, Email $email): JsonResponse|CustomerEmailResource
    {
        if (!$request->user()->tokenCan('CustomerEmails:Update')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $email->update($request->validated());

        if ((bool) $request->validated('is_default') === true) {
            Email::query()
                ->where('customer_id', '=', $customer->id)
                ->where('id', '!=', $email->id)
                ->update(['is_default' => false]);
        }

        return new CustomerEmailResource($email);
    }
}
