<?php

namespace App\Http\Controllers\API;

use App\Models\Address;
use App\Enums\AddressType;
use App\Models\Customer;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\ResponseFromFile;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use App\Http\Resources\Customer\CustomerAddressResource;
use App\Http\Requests\Customer\StoreCustomerAddressRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

#[Group('Customer Addresses', 'Customer Address Management API')]
#[ResponseFromFile(file: 'resources/api-responses/401.json', status: 401, description: 'Unauthorized')]
#[ResponseFromFile(file: 'resources/api-responses/404.json', status: 404, description: 'Not Found')]
#[ResponseFromFile(file: 'resources/api-responses/400.json', status: 404, description: 'Bad Request')]
#[ResponseFromFile(file: 'resources/api-responses/500.json', status: 500, description: 'Internal Server Error')]
class APICustomerAddressesController extends Controller
{
    use HelperTrait;

    /**
     * List Customer Addresses
     */
    #[ResponseFromApiResource(CustomerAddressResource::class, Address::class, status: 200, description: 'OK', collection: true, simplePaginate: 25)]
    #[QueryParam('type', required: false, enum: AddressType::class)]
    #[QueryParam('search', 'string', description: 'Search for customer addresses by name or any address fields', required: false, example: '25 Brookfield Road')]
    #[QueryParam('per_page', 'integer', description: 'Number of results per page (Min 25, Max 100)', required: false, example: 25)]
    #[QueryParam('page', 'integer', description: 'Page number', required: false, example: 1)]
    public function index(Request $request, Customer $customer): JsonResponse|AnonymousResourceCollection
    {
        if (!$request->user()->tokenCan('CustomerAddresses:Read')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customerAddresses = $customer->addresses()
            ->when($request->type,
                static fn ($query, $type) => $query
                    ->where('type', '=', $type)
            )
            ->when($request->search,
                static fn ($query, $search) => $query
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('line1', 'like', '%' . $search . '%')
                    ->orWhere('line2', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%')
                    ->orWhere('state', 'like', '%' . $search . '%')
                    ->orWhere('postal_code', 'like', '%' . $search . '%')
                    ->orWhere('country', 'like', '%' . $search . '%')
            )
            ->simplePaginate($this->clamp($request->input('per_page'), 25, 100));

        return CustomerAddressResource::collection($customerAddresses);
    }

    /**
     * Create Customer Address
     */
    #[ResponseFromApiResource(CustomerAddressResource::class, Address::class, status: 201, description: 'Created')]
    #[ResponseFromFile(file: 'resources/api-responses/422.json', status: 422, description: 'Validation Failed')]
    public function store(StoreCustomerAddressRequest $request, Customer $customer): JsonResponse|CustomerAddressResource
    {
        if (!$request->user()->tokenCan('CustomerAddresses:Create')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $address = Address::create(array_merge(
            ['customer_id' => $customer->id],
            $request->validated()
        ));

        if ((bool) $request->validated('is_default') === true) {
            Address::query()
                ->where('customer_id', '=', $customer->id)
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        return new CustomerAddressResource($address);
    }

    /**
     * Get Customer Address
     */
    #[ResponseFromApiResource(CustomerAddressResource::class, Address::class, status: 200, description: 'OK')]
    public function show(Request $request, Customer $customer, Address $address): JsonResponse|CustomerAddressResource
    {
        if (!$request->user()->tokenCan('CustomerAddresses:Read')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return new CustomerAddressResource($address);
    }

    /**
     * Update Customer Address
     */
    #[ResponseFromApiResource(CustomerAddressResource::class, Address::class, status: 200, description: 'OK')]
    #[ResponseFromFile(file: 'resources/api-responses/422.json', status: 422, description: 'Validation Failed')]
    public function update(StoreCustomerAddressRequest $request, Customer $customer, Address $address): JsonResponse|CustomerAddressResource
    {
        if (!$request->user()->tokenCan('CustomerAddresses:Update')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $address->update($request->validated());

        if ((bool) $request->validated('is_default') === true) {
            Address::query()
                ->where('customer_id', '=', $customer->id)
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        return new CustomerAddressResource($address);
    }
}
