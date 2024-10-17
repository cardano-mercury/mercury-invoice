<?php

namespace App\Http\Controllers\API;

use App\Models\Phone;
use App\Enums\PhoneType;
use App\Models\Customer;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use App\Http\Resources\CustomerPhoneResource;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use App\Http\Requests\Customer\StoreCustomerPhoneRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

#[Group('Customer Phones', 'Customer Phone Management API')]
class APICustomerPhonesController extends Controller
{
    use HelperTrait;

    /**
     * List Customer Phones
     */
    #[ResponseFromApiResource(CustomerPhoneResource::class, Phone::class, collection: true, simplePaginate: 25)]
    #[QueryParam('type', required: false, enum: PhoneType::class)]
    #[QueryParam('search', 'string', description: 'Search for customer phones by name or number', required: false, example: '0123456789')]
    #[QueryParam('per_page', 'integer', description: 'Number of results per page (Min 25, Max 100)', required: false, example: 25)]
    #[QueryParam('page', 'integer', description: 'Page number', required: false, example: 1)]
    public function index(Request $request, Customer $customer): JsonResponse|AnonymousResourceCollection
    {
        if (!$request->user()->tokenCan('CustomerPhones:Read')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customerPhones = $customer->phones()
            ->when($request->type,
                static fn ($query, $type) => $query
                    ->where('type', '=', $type)
            )
            ->when($request->search,
                static fn ($query, $search) => $query
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('number', 'like', '%' . $search . '%')
            )
            ->simplePaginate($this->clamp($request->input('per_page'), 25, 100));

        return CustomerPhoneResource::collection($customerPhones);
    }

    /**
     * Create Customer Phone
     */
    #[ResponseFromApiResource(CustomerPhoneResource::class, Phone::class)]
    public function store(StoreCustomerPhoneRequest $request, Customer $customer): CustomerPhoneResource|JsonResponse
    {
        if (!$request->user()->tokenCan('CustomerPhones:Create')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $phone = Phone::create(array_merge(
            ['customer_id' => $customer->id],
            $request->validated()
        ));

        if ((bool) $request->validated('is_default') === true) {
            Phone::query()
                ->where('customer_id', '=', $customer->id)
                ->where('id', '!=', $phone->id)
                ->update(['is_default' => false]);
        }

        return new CustomerPhoneResource($phone);
    }

    /**
     * Get Customer Phone
     */
    #[ResponseFromApiResource(CustomerPhoneResource::class, Phone::class)]
    public function show(Request $request, Customer $customer, Phone $phone): CustomerPhoneResource|JsonResponse
    {
        if (!$request->user()->tokenCan('CustomerPhones:Read')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return new CustomerPhoneResource($phone);
    }

    /**
     * Update Customer Phone
     */
    #[ResponseFromApiResource(CustomerPhoneResource::class, Phone::class)]
    public function update(StoreCustomerPhoneRequest $request, Customer $customer, Phone $phone): CustomerPhoneResource|JsonResponse
    {
        if (!$request->user()->tokenCan('CustomerPhones:Update')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $phone->update($request->validated());

        if ((bool) $request->validated('is_default') === true) {
            Phone::query()
                ->where('customer_id', '=', $customer->id)
                ->where('id', '!=', $phone->id)
                ->update(['is_default' => false]);
        }

        return new CustomerPhoneResource($phone);
    }
}
