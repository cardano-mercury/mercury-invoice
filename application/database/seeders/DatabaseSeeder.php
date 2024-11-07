<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\InvoiceActivity;
use App\Models\InvoiceItem;
use App\Models\InvoicePayment;
use App\Models\User;
use App\Models\Phone;
use App\Models\Email;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Service;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Webhook;
use App\Models\WebhookLog;
use Random\RandomException;
use Illuminate\Database\Seeder;
use App\Models\ProductCategory;
use App\Models\ServiceCategory;
use App\Models\CustomerCategory;
use App\Models\WebhookEventTarget;
use Illuminate\Support\Collection;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @throws RandomException
     */
    public function run(): void
    {
        if (app()->environment('local', 'staging'))
        {
            // Seed user
            $user = User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@local.dev',
            ]);

            // Seed customer
            $customer = Customer::factory()->create([
                'user_id' => $user->id,
            ]);

            // Seed emails
            $emails = Email::factory(random_int(random_int(2, 3), random_int(5, 10)))->create([
                'customer_id' => $customer->id,
            ]);

            // Seed phones
            Phone::factory(random_int(random_int(2, 3), random_int(5, 10)))->create([
                'customer_id' => $customer->id,
            ]);

            // Seed addresses
            $customerAddresses = Address::factory(random_int(random_int(2, 3), random_int(5, 10)))->create([
                'customer_id' => $customer->id,
            ]);

            // Seed products
            $products = Product::factory(random_int(random_int(2, 3), random_int(5, 10)))->create([
                'user_id' => $user->id,
            ]);

            // Seed services
            $services = Service::factory(random_int(random_int(2, 3), random_int(5, 10)))->create([
                'user_id' => $user->id,
            ]);

            // Seed categories
            $customerCategories = CustomerCategory::factory(random_int(4, random_int(5, 10)))->create([
                'user_id' => $user->id,
            ]);
            $productCategories = ProductCategory::factory(random_int(4, random_int(5, 10)))->create([
                'user_id' => $user->id,
            ]);
            $serviceCategories = ServiceCategory::factory(random_int(4, random_int(5, 10)))->create([
                'user_id' => $user->id,
            ]);

            // Associate random categories with customer
            $this->attachRandomCategoriesToEntity($customerCategories, $customer);

            // Associate random categories with products
            /** @var Product $product */
            foreach ($products as $product) {
                $this->attachRandomCategoriesToEntity($productCategories, $product);
            }

            // Associate random categories with services
            /** @var Service $service */
            foreach ($services as $service) {
                $this->attachRandomCategoriesToEntity($serviceCategories, $service);
            }

            // Seed webhook
            $webhook = Webhook::factory()->create([
                'user_id' => $user->id,
            ]);
            WebhookEventTarget::factory()->create([
                'webhook_id' => $webhook->id,
            ]);
            WebhookLog::factory()->create([
                'webhook_id' => $webhook->id,
            ]);

            /**
             * Fake Paid Invoice #1
             */

            // Seed invoice #1
            $invoice1 = Invoice::factory()->create([
                'user_id' => $user->id,
                'customer_id' => $customer->id,
                'billing_address_id' => $customerAddresses[0]->id,
                'shipping_address_id' => $customerAddresses[1]->id,
                'status' => Status::PAID,
            ]);

            // Seed invoice #1 Email Recipients
            $invoice1->recipients()->sync([
                $emails->first()->id,
            ]);

            // Seed invoice #1 item #1
            InvoiceItem::factory()->create([
                'invoice_id' => $invoice1->id,
                'sku' => $products->first()->sku,
                'description' => $products->first()->description,
                'quantity' => 2,
                'unit_price' => $products->first()->unit_price,
                'tax_rate' => 0.00,
            ]);

            // Seed invoice #1 item #2
            InvoiceItem::factory()->create([
                'invoice_id' => $invoice1->id,
                'sku' => null,
                'description' => $services->first()->description,
                'quantity' => 1,
                'unit_price' => $services->first()->unit_price,
                'tax_rate' => 15.00,
            ]);

            // Seed invoice #1 item #3
            InvoiceItem::factory()->create([
                'invoice_id' => $invoice1->id,
                'sku' => null,
                'description' => 'Discount for being awesome',
                'quantity' => 1,
                'unit_price' => -5,
                'tax_rate' => 0.00,
            ]);

            // Update invoice total
            $invoice1->load('items');
            $invoice1Total = 0;
            foreach ($invoice1->items as $item) {
                $subtotal = $item->quantity * $item->unit_price;
                $tax = $subtotal * ($item->tax_rate / 100);
                $invoice1Total += ($subtotal + $tax);
            }
            $invoice1->update(['total' => $invoice1Total]);

            // Seed invoice #1 activities
            InvoiceActivity::factory()->create([ 'invoice_id' => $invoice1->id, 'activity' => 'Saved Invoice' ]);
            InvoiceActivity::factory()->create([ 'invoice_id' => $invoice1->id, 'activity' => 'Updated Invoice' ]);
            InvoiceActivity::factory()->create([ 'invoice_id' => $invoice1->id, 'activity' => 'Published Invoice' ]);
            InvoiceActivity::factory()->create([ 'invoice_id' => $invoice1->id, 'activity' => 'Customer Viewed Invoice' ]);
            InvoiceActivity::factory()->create([ 'invoice_id' => $invoice1->id, 'activity' => 'Late Reminder Sent' ]);
            InvoiceActivity::factory()->create([ 'invoice_id' => $invoice1->id, 'activity' => 'Customer Viewed Invoice' ]);
            InvoiceActivity::factory()->create([ 'invoice_id' => $invoice1->id, 'activity' => 'Customer Paid Invoice' ]);

            # Seed invoice #1 payment
            $invoice1->fresh(['items']);
            InvoicePayment::factory()->create([
                'invoice_id' => $invoice1->id,
                'payment_amount' => $invoice1->total,
                'status' => Status::PAID,
            ]);

        }
    }

    /**
     * @throws RandomException
     */
    private function attachRandomCategoriesToEntity(Collection $entityCategories, Customer|Product|Service $entity): void
    {
        $allEntityCategoryIds = $entityCategories->pluck('id')->toArray();

        $randomCategoryIdIndexes = (array) array_rand($allEntityCategoryIds, random_int(1, 3));

        $randomCategoryIds = [];
        foreach ($randomCategoryIdIndexes as $randomCategoryIdIndex) {
            $randomCategoryIds[] = $allEntityCategoryIds[$randomCategoryIdIndex];
        }

        $entity->categories()->sync($randomCategoryIds);
    }
}
