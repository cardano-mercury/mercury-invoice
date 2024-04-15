<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Phone;
use App\Models\User;
use App\Models\Email;
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

            // Seed email
            Email::factory(2)->create([
                'customer_id' => $customer->id,
            ]);

            // Seed phone
            Phone::factory(3)->create([
                'customer_id' => $customer->id,
            ]);

            // Seed address
            Address::factory()->create([
                'customer_id' => $customer->id,
            ]);
        }
    }
}
