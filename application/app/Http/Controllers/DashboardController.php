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

    public function sendTestEmail(): RedirectResponse
    {
        $userEmail = auth()->user()->email;

        Mail::to($userEmail)
            ->send(new TestEmail());

        session()->flash('success', sprintf('A test email has been sent to: %s', $userEmail));

        return to_route('dashboard');
    }
}
