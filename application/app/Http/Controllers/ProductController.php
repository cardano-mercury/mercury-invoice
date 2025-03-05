<?php

namespace App\Http\Controllers;

use Throwable;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Product;
use App\Traits\HashIdTrait;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Traits\JsonDownloadTrait;
use Illuminate\Http\RedirectResponse;
use App\Http\Resources\Product\ProductResource;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\SyncProductCategoryRequest;
use App\Http\Requests\Product\StoreProductCategoryRequest;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
        $product->load(['categories']);
        $productCategories = ProductCategory::query()->where('user_id', auth()->id())->get();

        return Inertia::render('Product/Show', compact('product', 'productCategories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): Response
    {
        $product->load(['categories']);
        $productCategories = ProductCategory::query()->where('user_id', auth()->id())->get();

        return Inertia::render('Product/Edit', compact('product', 'productCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        session()->flash('success', 'Product info updated');

        return back();
    }

    /**
     * Update product categories
     */
    public function updateCategories(SyncProductCategoryRequest $request, Product $product): RedirectResponse
    {
        $product->categories()->sync($request->category_ids);

        session()->flash('success', 'Product successfully assigned to selected categories');

        return back();
    }

    /**
     * Store a new product category
     */
    public function storeCategory(StoreProductCategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        ProductCategory::create($validated);

        session()->flash('success', 'Category created successfully');

        return back();
    }

    /**
     * Update an existing product category
     */
    public function updateCategory(StoreProductCategoryRequest $request, ProductCategory $productCategory): RedirectResponse
    {
        $productCategory->update($request->validated());

        session()->flash('success', 'Category updated successfully');

        return back();
    }

    /**
     * Delete a product category and detach from all products
     */
    public function destroyCategory(ProductCategory $productCategory): RedirectResponse
    {
        $productCategory->products()->detach();

        $productCategory->delete();

        session()->flash('success', 'Category deleted successfully');

        return back();
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

    public function export(Request $request): StreamedResponse
    {
        $products = Product::query()
            ->where('user_id', auth()->id())
            ->with(['categories'])
            ->get();

        return $this->downloadZipCompressedJson(
            ProductResource::collection($products)->toResponse($request)->getData(true)['data'],
            'products-export',
        );
    }
}
