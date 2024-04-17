<?php

namespace App\Http\Controllers;

use Throwable;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Customer\StoreCustomerRequest;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $customers = Customer::query()
            ->where('user_id', Auth::id())
            ->get();

        return Inertia::render('Customer/Index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Customer/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        $Customer = Customer::create($validated);

        session()->flash('success', 'New customer created!');

        return to_route('customers.show', $Customer->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer): Response
    {
        return Inertia::render('Customer/Show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer): Response
    {
        return Inertia::render('Customer/Edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->validated());

        session()->flash('info', 'Customer record updated!');

        return to_route('customers.show', $customer->id);
    }

    /**
     * Remove the specified resource from storage.
     * @throws Throwable
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->deleteOrFail();

        // TODO: Handle child element's of customers (e.g. Emails, Phones, Addresses)

        session()->flash('success', 'Customer record deleted');

        return to_route('customers.index');
    }
}
