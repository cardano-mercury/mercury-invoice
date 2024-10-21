<?php

namespace App\Http\Controllers\API;

use App\Models\Service;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ServiceCategory;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\ResponseFromFile;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use App\Http\Resources\Service\ServiceCategoryResource;
use App\Http\Requests\Service\SyncServiceCategoryRequest;
use App\Http\Requests\Service\StoreServiceCategoryRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

#[Group('Service Categories', 'Service Category Management API')]
#[ResponseFromFile(file: 'resources/api-responses/401.json', status: 401, description: 'Unauthorized')]
#[ResponseFromFile(file: 'resources/api-responses/404.json', status: 404, description: 'Not Found')]
#[ResponseFromFile(file: 'resources/api-responses/400.json', status: 404, description: 'Bad Request')]
#[ResponseFromFile(file: 'resources/api-responses/500.json', status: 500, description: 'Internal Server Error')]
class APIServiceCategoriesController extends Controller
{
    use HelperTrait;

    /**
     * List Service Categories
     */
    #[ResponseFromApiResource(ServiceCategoryResource::class, ServiceCategory::class, status: 200, description: 'OK', collection: true, simplePaginate: 25)]
    #[QueryParam('search', 'string', description: 'Search for service categories by name', required: false, example: 'Online Support')]
    #[QueryParam('per_page', 'integer', description: 'Number of results per page (Min 25, Max 100)', required: false, example: 25)]
    #[QueryParam('page', 'integer', description: 'Page number', required: false, example: 1)]
    public function index(Request $request): JsonResponse|AnonymousResourceCollection
    {
        if (!$request->user()->tokenCan('ServiceCategories:Read')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $serviceCategories = ServiceCategory::query()
            ->where('user_id', $request->user()->id)
            ->when($request->search,
                static fn ($query, $search) => $query
                    ->where('name', 'like', '%' . $search . '%')
            )
            ->simplePaginate($this->clamp($request->input('per_page'), 25, 100));

        return ServiceCategoryResource::collection($serviceCategories);
    }

    /**
     * Create Service Category
     */
    #[ResponseFromApiResource(ServiceCategoryResource::class, ServiceCategory::class, status: 201, description: 'Created')]
    #[ResponseFromFile(file: 'resources/api-responses/422.json', status: 422, description: 'Validation Failed')]
    public function store(StoreServiceCategoryRequest $request): ServiceCategoryResource|JsonResponse
    {
        if (!$request->user()->tokenCan('ServiceCategories:Create')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $productCategory = ServiceCategory::create(array_merge(
            ['user_id' => $request->user()->id],
            $request->validated()
        ));

        return new ServiceCategoryResource($productCategory);
    }

    /**
     * Get Service Category
     */
    #[ResponseFromApiResource(ServiceCategoryResource::class, ServiceCategory::class, status: 200, description: 'OK')]
    public function show(Request $request, ServiceCategory $serviceCategory): ServiceCategoryResource|JsonResponse
    {
        if (!$request->user()->tokenCan('ServiceCategories:Read')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return new ServiceCategoryResource($serviceCategory);
    }

    /**
     * Update Service Category
     */
    #[ResponseFromApiResource(ServiceCategoryResource::class, ServiceCategory::class, status: 200, description: 'OK')]
    #[ResponseFromFile(file: 'resources/api-responses/422.json', status: 422, description: 'Validation Failed')]
    public function update(StoreServiceCategoryRequest $request, ServiceCategory $serviceCategory): ServiceCategoryResource|JsonResponse
    {
        if (!$request->user()->tokenCan('ServiceCategories:Update')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $serviceCategory->update($request->validated());

        return new ServiceCategoryResource($serviceCategory);
    }

    /**
     * Sync Service Categories
     */
    #[\Knuckles\Scribe\Attributes\Response(status: 204, description: 'Service Categories Synced')]
    #[ResponseFromFile(file: 'resources/api-responses/422.json', status: 422, description: 'Validation Failed')]
    public function sync(SyncServiceCategoryRequest $request): Response|JsonResponse
    {
        if (!$request->user()->tokenCan('ServiceCategories:Update')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $service = Service::query()
            ->where('user_id', $request->user()->id)
            ->where('id', $request->validated('service_id'))
            ->firstOrFail();

        $service->categories()->sync($request->validated('category_ids'));

        return response()->noContent();
    }
}
