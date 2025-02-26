<?php

namespace App\Http\Controllers;

use App\Enums\AddressType;
use App\Enums\PhoneType;
use Throwable;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Email;
use App\Models\Phone;
use App\Models\Address;
use App\Models\Customer;
use App\Traits\HashIdTrait;
use App\Models\CustomerCategory;
use Illuminate\Http\Request;
use App\Traits\JsonDownloadTrait;
use Illuminate\Http\RedirectResponse;
use App\Http\Resources\Customer\CustomerResource;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\StoreCustomerEmailRequest;
use App\Http\Requests\Customer\StoreCustomerPhoneRequest;
use App\Http\Requests\Customer\SyncCustomerCategoryRequest;
use App\Http\Requests\Customer\StoreCustomerAddressRequest;
use Illuminate\Contracts\Container\BindingResolutionException;

class CustomerController extends Controller
{
    use HashIdTrait;
    use JsonDownloadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $customers = Customer::query()
            ->where('user_id', auth()->id())
            ->orderBy('id', 'desc')
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
        $validated['user_id'] = auth()->id();
        $customer = Customer::create($validated);

        session()->flash('success', 'Customer record created');

        return to_route('customers.show', $customer->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer): Response
    {
        $customer->load([
            'emails',
            'phones',
            'addresses',
            'categories'
        ]);

        $customerCategories = $customer->categories;
        $phoneTypes = PhoneType::array();
        $addressTypes = AddressType::array();

        return Inertia::render('Customer/Show', compact(
            'customer',
            'customerCategories',
            'phoneTypes',
            'addressTypes'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer): Response
    {
        $customer->load([
            'emails',
            'phones',
            'addresses',
            'categories'
        ]);

        $customerCategories = $customer->categories;
        $phoneTypes = PhoneType::array();
        $addressTypes = AddressType::array();

        return Inertia::render('Customer/Edit', compact(
            'customer',
            'customerCategories',
            'phoneTypes',
            'addressTypes'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->validated());

        session()->flash('info', 'Customer info updated');

        return back();
    }

    /**
     * Store a new email for the customer
     */
    public function storeEmail(StoreCustomerEmailRequest $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validated();

        // If this is set as default, unset all other defaults
        if (!empty($validated['is_default'])) {
            $customer->emails()->update(['is_default' => false]);
        }

        $customer->emails()->create($validated);

        session()->flash('success', 'Email added to customer');

        return back();
    }

    /**
     * Update an existing email
     */
    public function updateEmail(StoreCustomerEmailRequest $request, Customer $customer, Email $email): RedirectResponse
    {
        $validated = $request->validated();

        // If this is set as default, unset all other defaults
        if (!empty($validated['is_default'])) {
            $customer->emails()->where('id', '!=', $email->id)->update(['is_default' => false]);
        }

        $email->update($validated);

        session()->flash('info', 'Email updated');

        return back();
    }

    /**
     * Delete an email
     */
    public function destroyEmail(Customer $customer, Email $email): RedirectResponse
    {
        $customer
            ->emails()
            ->where('id', $email->id)
            ->delete();

        session()->flash('info', 'Email removed');

        return back();
    }

    /**
     * Store a new phone for the customer
     */
    public function storePhone(StoreCustomerPhoneRequest $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validated();

        // If this is set as default, unset all other defaults
        if (!empty($validated['is_default'])) {
            $customer->phones()->update(['is_default' => false]);
        }

        $customer->phones()->create($validated);

        session()->flash('success', 'Phone added to customer');

        return back();
    }

    /**
     * Update an existing phone
     */
    public function updatePhone(StoreCustomerPhoneRequest $request, Customer $customer, Phone $phone): RedirectResponse
    {
        $validated = $request->validated();

        // If this is set as default, unset all other defaults
        if (!empty($validated['is_default'])) {
            $customer->phones()->where('id', '!=', $phone->id)->update(['is_default' => false]);
        }

        $phone->update($validated);

        session()->flash('info', 'Phone updated');

        return back();
    }

    /**
     * Delete a phone
     */
    public function destroyPhone(Customer $customer, Phone $phone): RedirectResponse
    {
        $customer
            ->phones()
            ->where('id', $phone->id)
            ->delete();

        session()->flash('info', 'Phone removed');

        return back();
    }

    /**
     * Store a new address for the customer
     */
    public function storeAddress(StoreCustomerAddressRequest $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validated();

        // If this is set as default, unset all other defaults
        if (!empty($validated['is_default'])) {
            $customer->addresses()->update(['is_default' => false]);
        }

        $customer->addresses()->create($validated);

        session()->flash('success', 'Address added to customer');

        return back();
    }

    /**
     * Update an existing address
     */
    public function updateAddress(StoreCustomerAddressRequest $request, Customer $customer, Address $address): RedirectResponse
    {
        $validated = $request->validated();

        // If this is set as default, unset all other defaults
        if (!empty($validated['is_default'])) {
            $customer->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($validated);

        session()->flash('info', 'Address updated');

        return back();
    }

    /**
     * Delete an address
     */
    public function destroyAddress(Customer $customer, Address $address): RedirectResponse
    {
        $customer
            ->addresses()
            ->where('id', $address->id)
            ->delete();

        session()->flash('info', 'Address removed');

        return back();
    }

    /**
     * Update customer categories
     */
    public function updateCategories(SyncCustomerCategoryRequest $request, Customer $customer): RedirectResponse
    {
        $customer->categories()->sync($request->category_ids);

        session()->flash('info', 'Customer categories updated');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     * @throws Throwable
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->emails()->delete();
        $customer->phones()->delete();
        $customer->addresses()->delete();
        $customer->categories()->detach();
        $customer->deleteOrFail();

        session()->flash('success', 'Customer record deleted');

        return to_route('customers.index');
    }

    /**
     * @throws BindingResolutionException
     */
    public function export(Request $request): \Illuminate\Http\Response
    {
        $customers = Customer::query()
            ->where('user_id', auth()->id())
            ->with([
                'categories',
                'defaultEmail',
                'defaultPhone',
                'defaultAddress',
            ])
            ->get();

        return $this->downloadJson(
            CustomerResource::collection($customers)->toResponse($request)->getData(true)['data'],
            'customers-export',
        );
    }
}
