<?php

namespace App\Http\Controllers\API;

use App\Models\Customer;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\ResponseFromFile;
use App\Http\Resources\Customer\CustomerResource;
use App\Http\Requests\Customer\StoreCustomerRequest;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

#[Group('Customers', 'Customer Management API')]
#[ResponseFromFile(file: 'resources/api-responses/401.json', status: 401, description: 'Unauthorized')]
#[ResponseFromFile(file: 'resources/api-responses/404.json', status: 404, description: 'Not Found')]
#[ResponseFromFile(file: 'resources/api-responses/400.json', status: 404, description: 'Bad Request')]
#[ResponseFromFile(file: 'resources/api-responses/500.json', status: 500, description: 'Internal Server Error')]
class APICustomersController extends Controller
{
    use HelperTrait;

    /**
     * List Customers
     */
    #[ResponseFromApiResource(CustomerResource::class, Customer::class, status: 200, description: 'OK', collection: true, simplePaginate: 25)]
    #[QueryParam('search', 'string', description: 'Search for customers by name or tax number', required: false, example: 'John Doe')]
    #[QueryParam('per_page', 'integer', description: 'Number of results per page (Min 25, Max 100)', required: false, example: 25)]
    #[QueryParam('page', 'integer', description: 'Page number', required: false, example: 1)]
    public function index(Request $request): JsonResponse|AnonymousResourceCollection
    {
        if (!$request->user()->tokenCan('Customers:Read')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customers = Customer::query()
            ->where('user_id', $request->user()->id)
            ->when($request->search,
                static fn ($query, $search) => $query
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('tax_number', 'like', '%' . $search . '%')
            )
            ->with([
                'categories',
                'defaultEmail',
                'defaultPhone',
                'defaultAddress',
            ])
            ->simplePaginate($this->clamp($request->input('per_page'), 25, 100));

        return CustomerResource::collection($customers);
    }

    /**
     * Create Customer
     */
    #[ResponseFromApiResource(CustomerResource::class, Customer::class, status: 201, description: 'Created')]
    #[ResponseFromFile(file: 'resources/api-responses/422.json', status: 422, description: 'Validation Failed')]
    public function store(StoreCustomerRequest $request): CustomerResource|JsonResponse
    {
        if (!$request->user()->tokenCan('Customers:Create')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customer = Customer::create(array_merge(
            ['user_id' => $request->user()->id],
            $request->validated()
        ));

        $customer->load([
            'categories',
            'defaultEmail',
            'defaultPhone',
            'defaultAddress',
        ]);

        return new CustomerResource($customer);
    }

    /**
     * Get Customer
     */
    #[ResponseFromApiResource(CustomerResource::class, Customer::class, status: 200, description: 'OK')]
    public function show(Request $request, Customer $customer): CustomerResource|JsonResponse
    {
        if (!$request->user()->tokenCan('Customers:Read')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customer->load([
            'categories',
            'defaultEmail',
            'defaultPhone',
            'defaultAddress',
        ]);

        return new CustomerResource($customer);
    }

    /**
     * Update Customer
     */
    #[ResponseFromApiResource(CustomerResource::class, Customer::class, status: 200, description: 'OK')]
    #[ResponseFromFile(file: 'resources/api-responses/422.json', status: 422, description: 'Validation Failed')]
    public function update(StoreCustomerRequest $request, Customer $customer): CustomerResource|JsonResponse
    {
        if (!$request->user()->tokenCan('Customers:Update')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customer->update($request->validated());

        $customer->load([
            'categories',
            'defaultEmail',
            'defaultPhone',
            'defaultAddress',
        ]);

        return new CustomerResource($customer);
    }
}
