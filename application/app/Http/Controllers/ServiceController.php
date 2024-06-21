<?php

namespace App\Http\Controllers;

use Throwable;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Service;
use App\Traits\HashIdTrait;
use App\Traits\JsonDownloadTrait;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Service\StoreServiceRequest;
use Illuminate\Contracts\Container\BindingResolutionException;

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
        return Inertia::render('Service/Show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service): Response
    {
        return Inertia::render('Service/Edit', compact('service'));
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

    /**
     * @throws BindingResolutionException
     */
    public function export(): \Illuminate\Http\Response
    {
        $services = Service::query()
            ->where('user_id', auth()->id())
            ->with(['categories'])
            ->get()
            ->map(function ($service) {
                return [
                    'id' => $this->encodeId($service->id),
                    'name' => $service->name,
                    'description' => $service->description,
                    'unit_price' => $service->unit_price,
                    'supplier' => $service->supplier,
                    'categories' => $service->categories->map(function($category) {
                        return [
                            'id' => $this->encodeId($category->id),
                            'name' => $category->name,
                        ];
                    }),
                ];
            })
            ->toArray();

        return $this->downloadJson(
            $services,
            'services-export',
        );
    }
}
