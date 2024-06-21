<?php

namespace App\Http\Controllers;

use Throwable;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Product;
use App\Traits\HashIdTrait;
use App\Traits\JsonDownloadTrait;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Product\StoreProductRequest;
use Illuminate\Contracts\Container\BindingResolutionException;

class ProductController extends Controller
{
    use HashIdTrait;
    use JsonDownloadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $products = Product::query()
            ->where('user_id', auth()->id())
            ->orderBy('id', 'desc')
            ->get();

        return Inertia::render('Product/Index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Product/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        $product = Product::create($validated);

        session()->flash('success', 'Product record created');

        return to_route('products.show', $product->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): Response
    {
        return Inertia::render('Product/Show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): Response
    {
        return Inertia::render('Product/Edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        session()->flash('success', 'Product record updated');

        return to_route('products.show', $product->id);
    }

    /**
     * Remove the specified resource from storage.
     * @throws Throwable
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->categories()->detach();
        $product->deleteOrFail();

        session()->flash('success', 'Product record deleted');

        return to_route('products.index');
    }

    /**
     * @throws BindingResolutionException
     */
    public function export(): \Illuminate\Http\Response
    {
        $products = Product::query()
            ->where('user_id', auth()->id())
            ->with(['categories'])
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $this->encodeId($product->id),
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'description' => $product->description,
                    'unit_type' => $product->unit_type,
                    'unit_price' => $product->unit_price,
                    'supplier' => $product->supplier,
                    'categories' => $product->categories->map(function($category) {
                        return [
                            'id' => $this->encodeId($category->id),
                            'name' => $category->name,
                        ];
                    }),
                ];
            })
            ->toArray();

        return $this->downloadJson(
            $products,
            'products-export',
        );
    }
}
