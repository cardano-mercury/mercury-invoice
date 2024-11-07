<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Invoice;

class DashboardController extends Controller
{
    public function index(): Response
    {
        /**
         * We should load up some default values here...
         * Number of customers
         * Number of products
         * Number of services
         * Number of categories
         * Number of total paid invoices
         * Number of total unpaid invoices (published)
         *
         * 7 days revenue generated in proper format (maybe shunt this into an API call instead?)
         * Last 7 days worth of invoices?
         */
        $invoices = Invoice::query()
            ->where('user_id', auth()->id())
            ->with(['customer'])
            ->orderBy('issue_date', 'desc')
            ->get();

        return Inertia::render('Dashboard', compact('invoices'));
    }
}
