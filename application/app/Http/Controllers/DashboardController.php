<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Mail\TestEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Dashboard');
    }
}
