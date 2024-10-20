<?php

namespace App\Http\Controllers\API;

use App\Models\Customer;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\CustomerCategory;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\ResponseFromFile;
use App\Http\Resources\CustomerCategoryResource;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use App\Http\Requests\Customer\SyncCustomerCategoryRequest;
use App\Http\Requests\Customer\StoreCustomerCategoryRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

#[Group('Customer Categories', 'Customer Category Management API')]
#[ResponseFromFile(file: 'resources/api-responses/401.json', status: 401, description: 'Unauthorized')]
#[ResponseFromFile(file: 'resources/api-responses/404.json', status: 404, description: 'Not Found')]
#[ResponseFromFile(file: 'resources/api-responses/400.json', status: 404, description: 'Bad Request')]
#[ResponseFromFile(file: 'resources/api-responses/500.json', status: 500, description: 'Internal Server Error')]
class APICustomerCategoriesController extends Controller
{
    use HelperTrait;

    /**
     * List Customer Categories
     */
    #[ResponseFromApiResource(CustomerCategoryResource::class, CustomerCategory::class, status: 200, description: 'OK', collection: true, simplePaginate: 25)]
    #[QueryParam('search', 'string', description: 'Search for customer categories by name', required: false, example: 'Freelance')]
    #[QueryParam('per_page', 'integer', description: 'Number of results per page (Min 25, Max 100)', required: false, example: 25)]
    #[QueryParam('page', 'integer', description: 'Page number', required: false, example: 1)]
    public function index(Request $request): JsonResponse|AnonymousResourceCollection
    {
        if (!$request->user()->tokenCan('CustomerCategories:Read')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customerCategories = CustomerCategory::query()
            ->where('user_id', $request->user()->id)
            ->when($request->search,
                static fn ($query, $search) => $query
                    ->where('name', 'like', '%' . $search . '%')
            )
            ->withCount('customers')
            ->simplePaginate($this->clamp($request->input('per_page'), 25, 100));

        return CustomerCategoryResource::collection($customerCategories);
    }

    /**
     * Create Customer Category
     */
    #[ResponseFromApiResource(CustomerCategoryResource::class, CustomerCategory::class, status: 201, description: 'Created')]
    #[ResponseFromFile(file: 'resources/api-responses/422.json', status: 422, description: 'Validation Failed')]
    public function store(StoreCustomerCategoryRequest $request): CustomerCategoryResource|JsonResponse
    {
        if (!$request->user()->tokenCan('CustomerCategories:Create')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customerCategory = CustomerCategory::create(array_merge(
            ['user_id' => $request->user()->id],
            $request->validated()
        ));

        return new CustomerCategoryResource($customerCategory);
    }

    /**
     * Get Customer Category
     */
    #[ResponseFromApiResource(CustomerCategoryResource::class, CustomerCategory::class, status: 200, description: 'OK')]
    public function show(Request $request, CustomerCategory $customerCategory): CustomerCategoryResource|JsonResponse
    {
        if (!$request->user()->tokenCan('CustomerCategories:Read')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customerCategory->loadCount('customers');

        return new CustomerCategoryResource($customerCategory);
    }

    /**
     * Update Customer Category
     */
    #[ResponseFromApiResource(CustomerCategoryResource::class, CustomerCategory::class, status: 200, description: 'OK')]
    #[ResponseFromFile(file: 'resources/api-responses/422.json', status: 422, description: 'Validation Failed')]
    public function update(StoreCustomerCategoryRequest $request, CustomerCategory $customerCategory): CustomerCategoryResource|JsonResponse
    {
        if (!$request->user()->tokenCan('CustomerCategories:Update')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customerCategory->update($request->validated());

        $customerCategory->loadCount('customers');

        return new CustomerCategoryResource($customerCategory);
    }

    /**
     * Sync Customer Categories
     */
    #[\Knuckles\Scribe\Attributes\Response(status: 204, description: 'Customer Categories Synced')]
    #[ResponseFromFile(file: 'resources/api-responses/422.json', status: 422, description: 'Validation Failed')]
    public function sync(SyncCustomerCategoryRequest $request): Response|JsonResponse
    {
        if (!$request->user()->tokenCan('CustomerCategories:Update')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customer = Customer::query()
            ->where('user_id', $request->user()->id)
            ->where('id', $request->validated('customer_id'))
            ->firstOrFail();

        $customer->categories()->sync($request->validated('category_ids'));

        return response()->noContent();
    }
}
