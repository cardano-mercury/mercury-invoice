<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Phone;
use App\Models\Email;
use App\Models\Product;
use App\Models\Service;
use App\Models\Address;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Random\RandomException;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @throws RandomException
     */
    public function run(): void
    {
        if (app()->environment('local'))
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
            $categories = Category::factory(random_int(4, random_int(5, 10)))->create([
                'user_id' => $user->id,
            ]);
            $allCategoryIds = $categories->pluck('id')->toArray();

            // Associate random categories with customer
            $this->attachRandomCategories($allCategoryIds, $customer);

            // Associate random categories with products
            /** @var Product $product */
            foreach ($products as $product) {
                $this->attachRandomCategories($allCategoryIds, $product);
            }

            // Associate random categories with services
            /** @var Service $service */
            foreach ($services as $service) {
                $this->attachRandomCategories($allCategoryIds, $service);
            }
        }
    }

    /**
     * @throws RandomException
     */
    private function attachRandomCategories(array $allCategoryIds, Customer|Product|Service $entity): void
    {
        $randomCategoryIdIndexes = (array) array_rand($allCategoryIds, random_int(1, 3));

        $randomCategoryIds = [];
        foreach ($randomCategoryIdIndexes as $randomCategoryIdIndex) {
            $randomCategoryIds[] = $allCategoryIds[$randomCategoryIdIndex];
        }

        $entity->categories()->sync($randomCategoryIds);
    }
}
