<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Service\StoreServiceRequest;
use App\Models\Service;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use App\Http\Resources\Service\ServiceResource;
use Knuckles\Scribe\Attributes\ResponseFromFile;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

#[Group('Services', 'Service Management API')]
#[ResponseFromFile(file: 'resources/api-responses/401.json', status: 401, description: 'Unauthorized')]
#[ResponseFromFile(file: 'resources/api-responses/404.json', status: 404, description: 'Not Found')]
#[ResponseFromFile(file: 'resources/api-responses/400.json', status: 404, description: 'Bad Request')]
#[ResponseFromFile(file: 'resources/api-responses/500.json', status: 500, description: 'Internal Server Error')]
class APIServicesController extends Controller
{
    use HelperTrait;

    /**
     * List Services
     */
    #[ResponseFromApiResource(ServiceResource::class, Service::class, status: 200, description: 'OK', collection: true, simplePaginate: 25)]
    #[QueryParam('search', 'string', description: 'Search for services by name', required: false, example: 'Software Development')]
    #[QueryParam('per_page', 'integer', description: 'Number of results per page (Min 25, Max 100)', required: false, example: 25)]
    #[QueryParam('page', 'integer', description: 'Page number', required: false, example: 1)]
    public function index(Request $request): JsonResponse|AnonymousResourceCollection
    {
        if (!$request->user()->tokenCan('Services:Read')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $services = Service::query()
            ->where('user_id', $request->user()->id)
            ->when($request->search,
                static fn ($query, $search) => $query
                    ->where('name', 'like', '%' . $search . '%')
            )
            ->simplePaginate($this->clamp($request->input('per_page'), 25, 100));

        return ServiceResource::collection($services);
    }

    /**
     * Create Service
     */
    #[ResponseFromApiResource(ServiceResource::class, Service::class, status: 201, description: 'Created')]
    #[ResponseFromFile(file: 'resources/api-responses/422.json', status: 422, description: 'Validation Failed')]
    public function store(StoreServiceRequest $request): JsonResponse|ServiceResource
    {
        if (!$request->user()->tokenCan('Services:Create')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $service = Service::create(array_merge(
            ['user_id' => $request->user()->id],
            $request->validated()
        ));

        return new ServiceResource($service);
    }

    /**
     * Get Service
     */
    #[ResponseFromApiResource(ServiceResource::class, Service::class, status: 200, description: 'OK')]
    public function show(Request $request, Service $service): JsonResponse|ServiceResource
    {
        if (!$request->user()->tokenCan('Services:Read')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return new ServiceResource($service);
    }

    /**
     * Update Service
     */
    #[ResponseFromApiResource(ServiceResource::class, Service::class, status: 200, description: 'OK')]
    #[ResponseFromFile(file: 'resources/api-responses/422.json', status: 422, description: 'Validation Failed')]
    public function update(StoreServiceRequest $request, Service $service): JsonResponse|ServiceResource
    {
        if (!$request->user()->tokenCan('Services:Update')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $service->update($request->validated());

        return new ServiceResource($service);
    }
}
