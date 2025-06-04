<?php

namespace App\Http\Controllers;

use Throwable;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Service;
use App\Traits\HashIdTrait;
use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use App\Traits\JsonDownloadTrait;
use Illuminate\Http\RedirectResponse;
use App\Http\Resources\Service\ServiceResource;
use App\Http\Requests\Service\StoreServiceRequest;
use App\Http\Requests\Service\SyncServiceCategoryRequest;
use App\Http\Requests\Service\StoreServiceCategoryRequest;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ServiceController extends Controller
{
    use HashIdTrait;
    use JsonDownloadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $services = Service::query()
            ->where('user_id', auth()->id())
            ->orderBy('id', 'desc')
            ->get();

        return Inertia::render('Service/Index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Service/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        $service = Service::create($validated);

        session()->flash('success', 'Service record created');

        return to_route('services.show', $service->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service): Response
    {
        $service->load(['categories']);
        $serviceCategories = ServiceCategory::query()->where('user_id', auth()->id())->get();
        
        return Inertia::render('Service/Show', compact('service', 'serviceCategories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service): Response
    {
        $service->load(['categories']);
        $serviceCategories = ServiceCategory::query()->where('user_id', auth()->id())->get();
        
        return Inertia::render('Service/Edit', compact('service', 'serviceCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreServiceRequest $request, Service $service): RedirectResponse
    {
        $service->update($request->validated());

        session()->flash('success', 'Service record updated');

        return to_route('services.show', $service->id);
    }
    
    /**
     * Update service categories
     */
    public function updateCategories(SyncServiceCategoryRequest $request, Service $service): RedirectResponse
    {
        $service->categories()->sync($request->category_ids);

        session()->flash('success', 'Service successfully assigned to selected categories');

        return back();
    }

    /**
     * Store a new service category
     */
    public function storeCategory(StoreServiceCategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        ServiceCategory::create($validated);

        session()->flash('success', 'Category created successfully');

        return back();
    }

    /**
     * Update an existing service category
     */
    public function updateCategory(StoreServiceCategoryRequest $request, ServiceCategory $serviceCategory): RedirectResponse
    {
        $serviceCategory->update($request->validated());

        session()->flash('success', 'Category updated successfully');

        return back();
    }

    /**
     * Delete a service category and detach from all services
     */
    public function destroyCategory(ServiceCategory $serviceCategory): RedirectResponse
    {
        $serviceCategory->services()->detach();

        $serviceCategory->delete();

        session()->flash('success', 'Category deleted successfully');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     * @throws Throwable
     */
    public function destroy(Service $service): RedirectResponse
    {
        $service->categories()->detach();
        $service->deleteOrFail();

        session()->flash('success', 'Service record deleted');

        return to_route('services.index');
    }

    public function export(Request $request): StreamedResponse
    {
        $services = Service::query()
            ->where('user_id', auth()->id())
            ->get();

        return $this->downloadZipCompressedJson(
            ServiceResource::collection($services)->toResponse($request)->getData(true)['data'],
            'services-export',
        );
    }
}
