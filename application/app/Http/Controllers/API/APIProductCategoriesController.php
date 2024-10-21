<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ProductCategory;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\ResponseFromFile;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use App\Http\Resources\Product\ProductCategoryResource;
use App\Http\Requests\Product\StoreProductCategoryRequest;
use App\Http\Resources\Product\SyncProductCategoryRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

#[Group('Product Categories', 'Product Category Management API')]
#[ResponseFromFile(file: 'resources/api-responses/401.json', status: 401, description: 'Unauthorized')]
#[ResponseFromFile(file: 'resources/api-responses/404.json', status: 404, description: 'Not Found')]
#[ResponseFromFile(file: 'resources/api-responses/400.json', status: 404, description: 'Bad Request')]
#[ResponseFromFile(file: 'resources/api-responses/500.json', status: 500, description: 'Internal Server Error')]
class APIProductCategoriesController extends Controller
{
    use HelperTrait;

    /**
     * List Product Categories
     */
    #[ResponseFromApiResource(ProductCategoryResource::class, ProductCategory::class, status: 200, description: 'OK', collection: true, simplePaginate: 25)]
    #[QueryParam('search', 'string', description: 'Search for product categories by name', required: false, example: 'Hosting')]
    #[QueryParam('per_page', 'integer', description: 'Number of results per page (Min 25, Max 100)', required: false, example: 25)]
    #[QueryParam('page', 'integer', description: 'Page number', required: false, example: 1)]
    public function index(Request $request): JsonResponse|AnonymousResourceCollection
    {
        if (!$request->user()->tokenCan('ProductCategories:Read')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $productCategories = ProductCategory::query()
            ->where('user_id', $request->user()->id)
            ->when($request->search,
                static fn ($query, $search) => $query
                    ->where('name', 'like', '%' . $search . '%')
            )
            ->simplePaginate($this->clamp($request->input('per_page'), 25, 100));

        return ProductCategoryResource::collection($productCategories);
    }

    /**
     * Create Product Category
     */
    #[ResponseFromApiResource(ProductCategoryResource::class, ProductCategory::class, status: 201, description: 'Created')]
    #[ResponseFromFile(file: 'resources/api-responses/422.json', status: 422, description: 'Validation Failed')]
    public function store(StoreProductCategoryRequest $request): JsonResponse|ProductCategoryResource
    {
        if (!$request->user()->tokenCan('ProductCategories:Create')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $productCategory = ProductCategory::create(array_merge(
            ['user_id' => $request->user()->id],
            $request->validated()
        ));

        return new ProductCategoryResource($productCategory);
    }

    /**
     * Get Product Category
     */
    #[ResponseFromApiResource(ProductCategoryResource::class, ProductCategory::class, status: 200, description: 'OK')]
    public function show(Request $request, ProductCategory $productCategory): JsonResponse|ProductCategoryResource
    {
        if (!$request->user()->tokenCan('ProductCategories:Read')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return new ProductCategoryResource($productCategory);
    }

    /**
     * Update Product Category
     */
    #[ResponseFromApiResource(ProductCategoryResource::class, ProductCategory::class, status: 200, description: 'OK')]
    #[ResponseFromFile(file: 'resources/api-responses/422.json', status: 422, description: 'Validation Failed')]
    public function update(StoreProductCategoryRequest $request, ProductCategory $productCategory): JsonResponse|ProductCategoryResource
    {
        if (!$request->user()->tokenCan('ProductCategories:Update')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $productCategory->update($request->validated());

        return new ProductCategoryResource($productCategory);
    }

    /**
     * Sync Customer Categories
     */
    #[\Knuckles\Scribe\Attributes\Response(status: 204, description: 'Customer Categories Synced')]
    #[ResponseFromFile(file: 'resources/api-responses/422.json', status: 422, description: 'Validation Failed')]
    public function sync(SyncProductCategoryRequest $request): Response|JsonResponse
    {
        if (!$request->user()->tokenCan('ProductCategories:Update')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $product = Product::query()
            ->where('user_id', $request->user()->id)
            ->where('id', $request->validated('product_id'))
            ->firstOrFail();

        $product->categories()->sync($request->validated('category_ids'));

        return response()->noContent();
    }
}
