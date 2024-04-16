<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use App\Models\Phone;
use App\Models\Email;
use App\Models\Product;
use App\Models\Service;
use App\Models\Address;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
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
            Email::factory(mt_rand(mt_rand(2, 3), mt_rand(5, 10)))->create([
                'customer_id' => $customer->id,
            ]);

            // Seed phones
            Phone::factory(mt_rand(mt_rand(2, 3), mt_rand(5, 10)))->create([
                'customer_id' => $customer->id,
            ]);

            // Seed addresses
            Address::factory(mt_rand(mt_rand(2, 3), mt_rand(5, 10)))->create([
                'customer_id' => $customer->id,
            ]);

            // Seed products
            $products = Product::factory(mt_rand(mt_rand(2, 3), mt_rand(5, 10)))->create([
                'user_id' => $user->id,
            ]);

            // Seed services
            $services = Service::factory(mt_rand(mt_rand(2, 3), mt_rand(5, 10)))->create([
                'user_id' => $user->id,
            ]);

            // Seed categories
            $categories = Category::factory(mt_rand(4, mt_rand(5, 10)))->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
