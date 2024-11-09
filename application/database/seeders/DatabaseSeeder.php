<?php

namespace Database\Seeders;

use App\Enums\Status;
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
use App\Models\InvoiceItem;
use App\Models\InvoicePayment;
use App\Models\InvoiceActivity;
use Illuminate\Database\Seeder;
use App\Models\ProductCategory;
use App\Models\ServiceCategory;
use App\Models\CustomerCategory;
use Illuminate\Support\Facades\DB;
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

            // Seed predictable personal access token (useful for local api testing)
            DB::table('personal_access_tokens')->insert([[
                "id" => 1,
                "tokenable_type" => "App\\Models\\User",
                "tokenable_id" => 1,
                "name" => "Dev Test",
                "token" => "97e7a181f6a6383eb5256fa40736af56113331bc85025986dcb7e6787ef6c4e4",
                "abilities" => "[\"Customers:Read\",\"Products:Read\",\"Services:Read\",\"Invoices:Read\",\"CustomerEmails:Create\",\"CustomerPhones:Update\",\"CustomerCategories:Read\",\"Products:Create\",\"ProductCategories:Update\",\"ServiceCategories:Read\",\"Invoices:Create\",\"Invoices:Update\",\"ServiceCategories:Create\",\"Products:Update\",\"CustomerCategories:Create\",\"CustomerAddresses:Read\",\"CustomerEmails:Update\",\"Customers:Create\",\"Customers:Update\",\"CustomerPhones:Read\",\"CustomerAddresses:Create\",\"CustomerCategories:Update\",\"ProductCategories:Read\",\"Services:Create\",\"ServiceCategories:Update\",\"Services:Update\",\"ProductCategories:Create\",\"CustomerAddresses:Update\",\"CustomerPhones:Create\",\"CustomerEmails:Read\"]",
                "last_used_at" => "2024-11-07 18:09:58",
                "expires_at" => null,
                "created_at" => "2024-11-07 18:07:44",
                "updated_at" => "2024-11-07 18:09:58",
            ]]);

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

            echo "Seeding fake invoices, this might take a while ... Please Wait!\n";

            /**
             * Fake Paid Invoices
             */
            Invoice::factory(200)->create([
                'user_id' => $user->id,
                'customer_id' => $customer->id,
                'billing_address_id' => $customerAddresses[0]->id,
                'shipping_address_id' => $customerAddresses[1]->id,
                'status' => Status::PAID,
            ])->each(static function (Invoice $invoice) use($emails) {

                $invoice->recipients()->sync([ $emails->first()->id ]);

                $invoiceDateTime = $invoice->created_at->toDateTimeString();

                $invoiceItems = InvoiceItem::factory(mt_rand(1, 5))->create([
                    'invoice_id' => $invoice->id,
                    'created_at' => $invoiceDateTime,
                    'updated_at' => $invoiceDateTime,
                ]);

                $invoiceTotal = 0;
                foreach ($invoiceItems as $item) {
                    $subtotal = $item->quantity * $item->unit_price;
                    $tax = $subtotal * ($item->tax_rate / 100);
                    $invoiceTotal += ($subtotal + $tax);
                }
                $invoice->update(['total' => $invoiceTotal]);

                InvoiceActivity::factory()->create([
                    'invoice_id' => $invoice->id,
                    'activity' => 'Dummy invoice created via database seeder',
                    'created_at' => $invoiceDateTime,
                    'updated_at' => $invoiceDateTime,
                ]);

                InvoicePayment::factory()->create([
                    'invoice_id' => $invoice->id,
                    'payment_amount' => $invoice->total,
                    'status' => Status::PAID,
                    'created_at' => $invoiceDateTime,
                    'updated_at' => $invoiceDateTime,
                ]);

            });

            /**
             * Fake Unpaid & Not Late Invoices
             */
            $dateInFuture = now()->addYears(2)->toDateString();
            Invoice::factory(50)->create([
                'user_id' => $user->id,
                'customer_id' => $customer->id,
                'billing_address_id' => $customerAddresses[0]->id,
                'shipping_address_id' => $customerAddresses[1]->id,
                'due_date' => $dateInFuture,
                'status' => Status::PUBLISHED,
            ])->each(static function (Invoice $invoice) use($emails) {

                $invoice->recipients()->sync([ $emails->first()->id ]);

                $invoiceDateTime = $invoice->created_at->toDateTimeString();

                $invoiceItems = InvoiceItem::factory(mt_rand(1, 5))->create([
                    'invoice_id' => $invoice->id,
                    'created_at' => $invoiceDateTime,
                    'updated_at' => $invoiceDateTime,
                ]);

                $invoiceTotal = 0;
                foreach ($invoiceItems as $item) {
                    $subtotal = $item->quantity * $item->unit_price;
                    $tax = $subtotal * ($item->tax_rate / 100);
                    $invoiceTotal += ($subtotal + $tax);
                }
                $invoice->update(['total' => $invoiceTotal]);

                InvoiceActivity::factory()->create([
                    'invoice_id' => $invoice->id,
                    'activity' => 'Dummy invoice created via database seeder',
                    'created_at' => $invoiceDateTime,
                    'updated_at' => $invoiceDateTime,
                ]);

            });

            /**
             * Fake Unpaid & Late Invoices
             */
            $dateInPast = now()->subYears(2)->toDateString();
            Invoice::factory(50)->create([
                'user_id' => $user->id,
                'customer_id' => $customer->id,
                'billing_address_id' => $customerAddresses[0]->id,
                'shipping_address_id' => $customerAddresses[1]->id,
                'due_date' => $dateInPast,
                'status' => Status::PUBLISHED,
            ])->each(static function (Invoice $invoice) use($emails) {

                $invoice->recipients()->sync([ $emails->first()->id ]);

                $invoiceDateTime = $invoice->created_at->toDateTimeString();

                $invoiceItems = InvoiceItem::factory(mt_rand(1, 5))->create([
                    'invoice_id' => $invoice->id,
                    'created_at' => $invoiceDateTime,
                    'updated_at' => $invoiceDateTime,
                ]);

                $invoiceTotal = 0;
                foreach ($invoiceItems as $item) {
                    $subtotal = $item->quantity * $item->unit_price;
                    $tax = $subtotal * ($item->tax_rate / 100);
                    $invoiceTotal += ($subtotal + $tax);
                }
                $invoice->update(['total' => $invoiceTotal]);

                InvoiceActivity::factory()->create([
                    'invoice_id' => $invoice->id,
                    'activity' => 'Dummy invoice created via database seeder',
                    'created_at' => $invoiceDateTime,
                    'updated_at' => $invoiceDateTime,
                ]);

            });

            /**
             * Fake Invoices In Random Statuses
             */
            Invoice::factory(50)->create([
                'user_id' => $user->id,
                'customer_id' => $customer->id,
                'billing_address_id' => $customerAddresses[0]->id,
                'shipping_address_id' => $customerAddresses[1]->id,
            ])->each(static function (Invoice $invoice) use($emails) {

                $invoice->recipients()->sync([ $emails->first()->id ]);

                $invoiceDateTime = $invoice->created_at->toDateTimeString();

                $invoiceItems = InvoiceItem::factory(mt_rand(1, 5))->create([
                    'invoice_id' => $invoice->id,
                    'created_at' => $invoiceDateTime,
                    'updated_at' => $invoiceDateTime,
                ]);

                $invoiceTotal = 0;
                foreach ($invoiceItems as $item) {
                    $subtotal = $item->quantity * $item->unit_price;
                    $tax = $subtotal * ($item->tax_rate / 100);
                    $invoiceTotal += ($subtotal + $tax);
                }
                $invoice->update(['total' => $invoiceTotal]);

                InvoiceActivity::factory()->create([
                    'invoice_id' => $invoice->id,
                    'activity' => 'Dummy invoice created via database seeder',
                    'created_at' => $invoiceDateTime,
                    'updated_at' => $invoiceDateTime,
                ]);

            });

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
