<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index(): Response {
        $customers = Customer::where('user_id', Auth::user()->id)
                             ->get();

        return Inertia::render('Customer/Index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {

        return Inertia::render('Customer/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $parsed_data = $request->validate([
            'name'       => [
                'required',
                'max:64',
            ],
            'tax_number' => [
                'max:64',
            ],
            'tax_rate'   => [
                'min:0',
                'max:100.00',
            ],
        ]);

        $parsed_data['user_id'] = Auth::user()->id;
        $Customer               = Customer::create($parsed_data);

        $request->session()
                ->flash('success', 'New customer created!');

        return to_route('customers.show', $Customer->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer) {
        return Inertia::render('Customer/Show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer) {
        return Inertia::render('Customer/Edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer) {
        $parsed_data = $request->validate([
            'name'       => [
                'required',
                'max:64',
            ],
            'tax_number' => [
                'max:64',
            ],
            'tax_rate'   => [
                'min:0',
                'max:100.00',
            ],
        ]);

        $customer->name = $parsed_data['name'];
        $customer->tax_number = $parsed_data['tax_number'];
        $customer->tax_rate = $parsed_data['tax_rate'];

        $customer->save();
        $request->session()->flash('info', 'Customer record updated!');
        return to_route('customers.show', $customer->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Customer $customer) {
        // destroy the specified customer here...
        $customer->deleteOrFail();
        $request->session()
                ->flash('success', 'Customer record removed');

        return to_route('customers.index');
    }
}
