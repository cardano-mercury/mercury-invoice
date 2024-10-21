<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use App\Http\Resources\Product\ProductResource;
use Knuckles\Scribe\Attributes\ResponseFromFile;
use App\Http\Requests\Product\StoreProductRequest;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

#[Group('Products', 'Product Management API')]
#[ResponseFromFile(file: 'resources/api-responses/401.json', status: 401, description: 'Unauthorized')]
#[ResponseFromFile(file: 'resources/api-responses/404.json', status: 404, description: 'Not Found')]
#[ResponseFromFile(file: 'resources/api-responses/400.json', status: 404, description: 'Bad Request')]
#[ResponseFromFile(file: 'resources/api-responses/500.json', status: 500, description: 'Internal Server Error')]
class APIProductsController extends Controller
{
    use HelperTrait;

    /**
     * List Products
     */
    #[ResponseFromApiResource(ProductResource::class, Product::class, status: 200, description: 'OK', collection: true, simplePaginate: 25)]
    #[QueryParam('search', 'string', description: 'Search for products by name or sku', required: false, example: 'Website Hosting')]
    #[QueryParam('per_page', 'integer', description: 'Number of results per page (Min 25, Max 100)', required: false, example: 25)]
    #[QueryParam('page', 'integer', description: 'Page number', required: false, example: 1)]
    public function index(Request $request): JsonResponse|AnonymousResourceCollection
    {
        if (!$request->user()->tokenCan('Products:Read')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customers = Product::query()
            ->where('user_id', $request->user()->id)
            ->when($request->search,
                static fn ($query, $search) => $query
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%')
            )
            ->simplePaginate($this->clamp($request->input('per_page'), 25, 100));

        return ProductResource::collection($customers);
    }

    /**
     * Create Product
     */
    #[ResponseFromApiResource(ProductResource::class, Product::class, status: 201, description: 'Created')]
    #[ResponseFromFile(file: 'resources/api-responses/422.json', status: 422, description: 'Validation Failed')]
    public function store(StoreProductRequest $request): JsonResponse|ProductResource
    {
        if (!$request->user()->tokenCan('Products:Create')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $product = Product::create(array_merge(
            ['user_id' => $request->user()->id],
            $request->validated()
        ));

        return new ProductResource($product);
    }

    /**
     * Get Product
     */
    #[ResponseFromApiResource(ProductResource::class, Product::class, status: 200, description: 'OK')]
    public function show(Request $request, Product $product): JsonResponse|ProductResource
    {
        if (!$request->user()->tokenCan('Products:Read')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return new ProductResource($product);
    }

    /**
     * Update Product
     */
    #[ResponseFromApiResource(ProductResource::class, Product::class, status: 200, description: 'OK')]
    #[ResponseFromFile(file: 'resources/api-responses/422.json', status: 422, description: 'Validation Failed')]
    public function update(StoreProductRequest $request, Product $product): JsonResponse|ProductResource
    {
        if (!$request->user()->tokenCan('Products:Update')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $product->update($request->validated());

        return new ProductResource($product);
    }
}
