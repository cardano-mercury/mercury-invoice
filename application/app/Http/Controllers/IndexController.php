<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class IndexController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'merchantCount' => $this->getMerchantCount(),
        ]);
    }

    /**
     * Get the merchant count rounded to nearest 100, cached for 1 hour.
     */
    private function getMerchantCount(): int
    {
        return Cache::remember('merchant_count', 3600, function () {
            $count = User::count();

            // Round to nearest 100, minimum 100
            return max(100, (int) (floor($count / 100) * 100));
        });
    }
}
