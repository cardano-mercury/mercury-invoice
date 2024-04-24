<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Webhook;
use Illuminate\Support\Str;
use App\Enums\HMACAlgorithm;
use Illuminate\Http\Request;
use App\Models\WebhookEventTarget;
use App\Enums\WebhookEventTargetName;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Webhook\StoreWebhookRequest;

class WebhookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $webhooks = Webhook::query()
            ->where('user_id', auth()->id())
            ->with('eventTargets')
            ->orderBy('id', 'desc')
            ->get();

        return Inertia::render('Webhook/Index', [
            'webhooks' => $webhooks,
            'hmacAlgorithms' => HMACAlgorithm::values(),
            'eventTargetNames' => WebhookEventTargetName::values(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWebhookRequest $request): RedirectResponse
    {
        $hmacSecret = Str::random(128);

        $validated = $request->validated() + [
            'user_id' => auth()->id(),
            'hmac_secret' => encrypt($hmacSecret),
        ];

        $webhook = Webhook::create($validated);

        $validTargetEvents = array_values(array_intersect($request->input('target_events'), WebhookEventTargetName::values()));

        if (count($validTargetEvents)) {
            $webhookEventTargets = [];
            foreach ($validTargetEvents as $validTargetEvent) {
                $webhookEventTargets[] = [
                    'webhook_id' => $webhook->id,
                    'event_name' => $validTargetEvent,
                ];
                WebhookEventTarget::insert($webhookEventTargets);
            }
        }

        return back()->with('flash', [
            'secret' => $hmacSecret,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreWebhookRequest $request, Webhook $webhook): RedirectResponse
    {
        $webhook->update($request->validated());

        $webhook->eventTargets()->delete();

        $validTargetEvents = array_values(array_intersect($request->input('target_events'), WebhookEventTargetName::values()));

        if (count($validTargetEvents)) {
            $webhookEventTargets = [];
            foreach ($validTargetEvents as $validTargetEvent) {
                $webhookEventTargets[] = [
                    'webhook_id' => $webhook->id,
                    'event_name' => $validTargetEvent,
                ];
                WebhookEventTarget::insert($webhookEventTargets);
            }
        }

        session()->flash('success', sprintf(
            'Webhook integration to (%s) has been updated.',
            $webhook->url,
        ));

        return back(303);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Webhook $webhook): RedirectResponse
    {
        $webhook->eventTargets()->delete();
        $webhook->logs()->delete();
        $webhook->delete();

        session()->flash('success', sprintf(
            'Webhook integration to (%s) has been deleted.',
            $webhook->url,
        ));

        return back(303);
    }
}
