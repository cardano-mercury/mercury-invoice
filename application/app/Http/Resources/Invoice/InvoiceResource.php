<?php

namespace App\Http\Resources\Invoice;

use App\Models\Email;
use App\Models\Address;
use App\Models\Customer;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use App\Models\InvoicePayment;
use App\Models\InvoiceActivity;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Customer\CustomerResource;
use App\Http\Resources\Customer\CustomerEmailResource;
use App\Http\Resources\Customer\CustomerAddressResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**
         * NOTE: This is a hacky workaround to ensure Scribe api documentation package generates
         * correct example response for nested relationship, when generating api docs.
         */
        if ($request->server('SERVER_NAME') === 'localhost' &&
            $request->server('HTTP_USER_AGENT') === 'Symfony' &&
            $request->routeIs('api.invoices.*')
        ) {
            $items = InvoiceItemResource::collection(InvoiceItem::factory(2)->make());
            if ($request->routeIs('api.invoices.show', 'api.invoices.store')) {
                $customer = new CustomerResource(Customer::factory()->make());
                $billingAddress = new CustomerAddressResource(Address::factory()->make());
                $shippingAddress = new CustomerAddressResource(Address::factory()->make());
                $notificationRecipients = CustomerEmailResource::collection(Email::factory(2)->make());
                $payments = InvoicePaymentResource::collection(InvoicePayment::factory(1)->make());
                $activities = InvoiceActivityResource::collection(InvoiceActivity::factory(1)->make());
            } else {
                $customer = CustomerResource::make($this->whenLoaded('customer'));
                $billingAddress = CustomerAddressResource::make($this->whenLoaded('billingAddress'));
                $shippingAddress = CustomerAddressResource::make($this->whenLoaded('shippingAddress'));
                $notificationRecipients = CustomerEmailResource::collection($this->whenLoaded('recipients'));
                $payments = InvoicePaymentResource::collection($this->whenLoaded('payments'));
                $activities = InvoiceActivityResource::collection($this->whenLoaded('activities'));
            }
        } else {
            $items = InvoiceItemResource::collection($this->items);
            $customer = CustomerResource::make($this->whenLoaded('customer'));
            $billingAddress = CustomerAddressResource::make($this->whenLoaded('billingAddress'));
            $shippingAddress = CustomerAddressResource::make($this->whenLoaded('shippingAddress'));
            $notificationRecipients = CustomerEmailResource::collection($this->whenLoaded('recipients'));
            $payments = InvoicePaymentResource::collection($this->whenLoaded('payments'));
            $activities = InvoiceActivityResource::collection($this->whenLoaded('activities'));
        }

        return [
            'id' => ($this->id ?? 1),
            'customer_id' => $this->customer_id,
            'billing_address_id' => $this->billing_address_id,
            'shipping_address_id' => $this->shipping_address_id,
            'invoice_reference' => $this->invoice_reference,
            'customer_reference' => $this->customer_reference,
            'currency' => $this->currency,
            'total' => (float) $this->total,
            'issue_date' => $this->issue_date->format('Y-m-d'),
            'due_date' => $this->due_date->format('Y-m-d'),
            'last_notified' => $this->last_notified?->toDateTimeString(),
            'is_overdue' => $this->is_overdue,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'items' => $items,
            'customer' => $customer,
            'billing_address' => $billingAddress,
            'shipping_address' => $shippingAddress,
            'notification_recipients' => $notificationRecipients,
            'payments' => $payments,
            'activities' => $activities,
        ];
    }
}
