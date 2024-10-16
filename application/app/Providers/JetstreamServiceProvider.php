<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);
    }

    /**
     * Configure the permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions([
            'Customers:Read',
            'CustomersEmails:Read',
            'CustomersPhones:Read',
            'CustomersAddresses:Read',
            'CustomersCategories:Read',
            'Products:Read',
            'ProductsCategories:Read',
            'Services:Read',
            'ServicesCategories:Read',
            'Invoices:Read',
        ]);

        Jetstream::permissions([
            // Customers
            'Customers:Read',
            'Customers:Create',
            'Customers:Update',
            // Customer Emails
            'CustomerEmails:Read',
            'CustomerEmails:Create',
            'CustomerEmails:Update',
            // Customer Phones
            'CustomerPhones:Read',
            'CustomerPhones:Create',
            'CustomerPhones:Update',
            // Customer Addresses
            'CustomerAddresses:Read',
            'CustomerAddresses:Create',
            'CustomerAddresses:Update',
            // Customer Categories
            'CustomerCategories:Read',
            'CustomerCategories:Create',
            'CustomerCategories:Update',
            // Products
            'Products:Read',
            'Products:Create',
            'Products:Update',
            // Product Categories
            'ProductCategories:Read',
            'ProductCategories:Create',
            'ProductCategories:Update',
            // Services
            'Services:Read',
            'Services:Create',
            'Services:Update',
            // Service Categories
            'ServiceCategories:Read',
            'ServiceCategories:Create',
            'ServiceCategories:Update',
            // Invoices
            'Invoices:Read',
            'Invoices:Create',
            'Invoices:Update',
        ]);
    }
}
