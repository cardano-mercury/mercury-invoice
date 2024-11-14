<?php

namespace App\Services;

use App\Enums\Status;
use App\Http\Resources\Invoice\InvoiceResource;
use App\Models\Webhook;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Service;
use App\Models\Customer;
use App\Jobs\WebhookNotificationJob;
use App\Enums\WebhookEventTargetName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Resources\Service\ServiceResource;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Customer\CustomerResource;

class WebhookService
{
    public static function handle(Model $model, WebhookEventTargetName $eventTargetName): void
    {
        if ($registeredWebhooks = self::getRegisteredWebhooks($model, $eventTargetName)) {
            switch (get_class($model)) {
                case Customer::class: self::handleCustomer($model, $eventTargetName, $registeredWebhooks); break;
                case Product::class: self::handleProduct($model, $eventTargetName, $registeredWebhooks); break;
                case Service::class: self::handleService($model, $eventTargetName, $registeredWebhooks); break;
                case Invoice::class: self::handleInvoice($model, $eventTargetName, $registeredWebhooks); break;
            }
        }
    }

    public static function getRegisteredWebhooks(Model $model, WebhookEventTargetName $eventTargetName): Collection|null
    {
        $registeredWebhooks = Webhook::query()
            ->where('user_id', $model->user_id ?? null)
            ->whereHas('eventTargets', function ($query) use ($eventTargetName) {
                $query->where('event_name', $eventTargetName->value);
            })
            ->get();

        if ($registeredWebhooks->count()) {
            return $registeredWebhooks;
        }

        return null;
    }

    public static function handleCustomer(Customer $customer, WebhookEventTargetName $eventTargetName, Collection $registeredWebhooks): void
    {
        $customer->load([
            'categories',
            'defaultEmail',
            'defaultPhone',
            'defaultAddress',
        ]);

        $payload = [
            'event_target_id' => $customer->id,
            'event_name' => $eventTargetName->value,
            'customer' => CustomerResource::make($customer)->toResponse(request())->getData(true)['data'],
        ];

        foreach ($registeredWebhooks as $registeredWebhook) {
            dispatch(new WebhookNotificationJob($registeredWebhook, $payload));
        }
    }

    public static function handleProduct(Product $product, WebhookEventTargetName $eventTargetName, Collection $registeredWebhooks): void
    {
        $payload = [
            'event_target_id' => $product->id,
            'event_name' => $eventTargetName->value,
            'product' => ProductResource::make($product)->toResponse(request())->getData(true)['data'],
        ];

        foreach ($registeredWebhooks as $registeredWebhook) {
            dispatch(new WebhookNotificationJob($registeredWebhook, $payload));
        }
    }

    public static function handleService(Service $service, WebhookEventTargetName $eventTargetName, Collection $registeredWebhooks): void
    {
        $payload = [
            'event_target_id' => $service->id,
            'event_name' => $eventTargetName->value,
            'service' => ServiceResource::make($service)->toResponse(request())->getData(true)['data'],
        ];

        foreach ($registeredWebhooks as $registeredWebhook) {
            dispatch(new WebhookNotificationJob($registeredWebhook, $payload));
        }
    }

    public static function handleInvoice(Invoice $invoice, WebhookEventTargetName $eventTargetName, Collection $registeredWebhooks): void
    {
        $invoice->load([
            'customer',
            'items',
            'billingAddress',
            'shippingAddress',
            'recipients',
            'payments',
            'activities',
        ]);

        $payload = [
            'event_target_id' => $invoice->id,
            'event_name' => $eventTargetName->value,
            'invoice' => InvoiceResource::make($invoice)->toResponse(request())->getData(true)['data'],
        ];

        foreach ($registeredWebhooks as $registeredWebhook) {
            dispatch(new WebhookNotificationJob($registeredWebhook, $payload));
        }
    }
}
