<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Phone;
use App\Models\Email;
use App\Models\Product;
use App\Models\Service;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Webhook;
use App\Models\WebhookEventTarget;
use App\Models\WebhookLog;
use Random\RandomException;
use Illuminate\Database\Seeder;
use App\Models\ProductCategory;
use App\Models\ServiceCategory;
use App\Models\CustomerCategory;
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
            Email::factory(random_int(random_int(2, 3), random_int(5, 10)))->create([
                'customer_id' => $customer->id,
            ]);

            // Seed phones
            Phone::factory(random_int(random_int(2, 3), random_int(5, 10)))->create([
                'customer_id' => $customer->id,
            ]);

            // Seed addresses
            Address::factory(random_int(random_int(2, 3), random_int(5, 10)))->create([
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
            WebhookLog::factory(random_int(5, 12))->create([
                'webhook_id' => $webhook->id,
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
