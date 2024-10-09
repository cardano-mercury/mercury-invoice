<?php

namespace App\Http\Controllers;

use App\Enums\CardanoNetwork;
use App\Models\User;
use App\ThirdParty\BlockfrostClient;
use Throwable;
use Inertia\Inertia;
use Inertia\Response;
use Stripe\StripeClient;
use App\Traits\HashIdTrait;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class SettingController extends Controller
{
    use HashIdTrait;

    public function index(): Response
    {
        $supportedCurrencies = config('cardanomercury.supported_currencies');
        $stripePaymentGatewayEnabled = !empty(auth()->user()->stripe_config['secret_key']);
        $cryptoPaymentGatewayEnabled = !empty(auth()->user()->crypto_config['api_key']);
        $cryptoPaymentAddress = auth()->user()->crypto_config['payment_address'] ?? null;
        $targetCardanoNetwork = config('cardanomercury.target_cardano_network');

        return Inertia::render('Setting/Index', compact(
            'supportedCurrencies',
            'stripePaymentGatewayEnabled',
            'cryptoPaymentGatewayEnabled',
            'cryptoPaymentAddress',
            'targetCardanoNetwork',
        ));
    }

    public function saveBusinessInfo(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag(
            'updateBusinessInfo',
            [
                'account_currency' => ['required', 'string', 'in:' . implode(',', array_keys(config('cardanomercury.supported_currencies')))],
                'business_name' => ['required', 'min:3', 'max:64'],
                'business_terms' => ['nullable', 'min:3', 'max:512'],
            ]
        );

        session()->flash('success', 'Business Info successfully updated.');

        auth()->user()->update($validated);

        return redirect()->back();
    }

    public function saveStripeConfig(Request $request): RedirectResponse
    {
        $enableStripePayment = ($request->get('enable_stripe_payment', false));

        $stripe = null;

        $validated = $request->validateWithBag(
            'updateStripeConfig',
            [
                'secret_key' => [($enableStripePayment ? 'required' : 'nullable'), 'string', static function (string $attribute, string $value, $fail) use(&$stripe) {
                    $isProduction = app()->environment('production');
                    if ($isProduction && !str_contains($value, 'live')) {
                        $fail('Please provide production stripe api credentials.');
                        return;
                    }
                    if (!$isProduction && !str_contains($value, 'test')) {
                        $fail('Please provide test stripe api credentials.');
                        return;
                    }
                    try {
                        $stripe = new StripeClient($value);
                        $stripe->customers->search([
                            'query' => 'name:\'testCardanoMercuryInvoice\'',
                        ]);
                    } catch (Throwable) {
                        $fail('The secret key provided is invalid.');
                    }
                }],
            ],
        );

        if ($enableStripePayment && !empty($validated['secret_key'])) {

            if (app()->environment('local') && !empty(env('DEV_STRIPE_WEBHOOK_HANDLER'))) {
                $targetWebhookUrl = env('DEV_STRIPE_WEBHOOK_HANDLER');
            } else {
                $targetWebhookUrl = route('incoming-webhooks.stripe', $this->encodeId(auth()->id()));
            }

            $webhookAlreadyRegistered = false;
            $webhookEndpointSecret = !empty(auth()->user()->stripe_config['endpoint_secret']) ? decrypt(auth()->user()->stripe_config['endpoint_secret']) : null;
            foreach ($stripe->webhookEndpoints->all()->data as $webhookData) {
                $webhookAlreadyRegistered = ($webhookData->url === $targetWebhookUrl);
                if ($webhookAlreadyRegistered) {
                    break;
                }
            }

            if (!$webhookAlreadyRegistered) {
                $endpoint = $stripe->webhookEndpoints->create([
                    'url' => $targetWebhookUrl,
                    'enabled_events' => [
                        'checkout.session.completed',
                    ],
                ]);
                $webhookEndpointSecret = $endpoint->secret;
            }

            $stripeConfig = json_encode([
                'secret_key' => encrypt($validated['secret_key']),
                'endpoint_secret' => encrypt($webhookEndpointSecret),
            ]);

            session()->flash('success', 'Stripe Payment Gateway successfully enabled.');

        } else {

            session()->flash('success', 'Stripe Payment Gateway successfully disabled.');

            $stripeConfig = null;

        }

        User::query()
            ->where('id', auth()->id())
            ->update([
                'stripe_config' => $stripeConfig,
            ]);

        return redirect()->back();
    }

    public function saveCryptoConfig(Request $request): RedirectResponse
    {
        $enableCryptoPayment = ($request->get('enable_crypto_payment', false));

        /** @var CardanoNetwork $targetCardanoNetwork */
        $targetCardanoNetwork = config('cardanomercury.target_cardano_network');

        $validated = $request->validateWithBag(
            'updateCryptoConfig',
            [
                'payment_address' => [($enableCryptoPayment ? 'required' : 'nullable'), static function (string $attribute, string $value, $fail) use($targetCardanoNetwork) {
                    if ($targetCardanoNetwork === CardanoNetwork::MAINNET && str_contains($value, '_test')) {
                        $fail('Please provide mainnet payment address.');
                        return;
                    }
                    if ($targetCardanoNetwork === CardanoNetwork::PREPROD && !str_contains($value, '_test')) {
                        $fail('Please provide preprod payment address.');
                        return;
                    }
                    if (!preg_match('/^addr' . ($targetCardanoNetwork === CardanoNetwork::PREPROD ? '_test' : '') . '1([qpzry9x8gf2tvdw0s3jn54khce6mua7l]{53})([qpzry9x8gf2tvdw0s3jn54khce6mua7l]{45})?$/', $value)) {
                        $fail('That payment address is invalid.');
                    }
                }],
                'api_key' => [($enableCryptoPayment ? 'required' : 'nullable'), static function (string $attribute, string $value, $fail) use($targetCardanoNetwork) {
                    if ($targetCardanoNetwork === CardanoNetwork::MAINNET && !str_contains($value, 'mainnet')) {
                        $fail('Please provide mainnet blockfrost api key / project id.');
                        return;
                    }
                    if ($targetCardanoNetwork === CardanoNetwork::PREPROD && !str_contains($value, 'preprod')) {
                        $fail('Please provide preprod blockfrost api key / project id.');
                        return;
                    }
                    try {
                        (new BlockfrostClient($targetCardanoNetwork, $value))->get('epochs/latest');
                    } catch (Throwable) {
                        $fail('The blockfrost api key / project id provided is invalid.');
                    }
                }],
            ],
        );

        if ($enableCryptoPayment && !empty($validated['api_key'])) {

            $cryptoConfig = json_encode([
                'payment_address' => $validated['payment_address'],
                'api_key' => encrypt($validated['api_key']),
                'cardano_network' => $targetCardanoNetwork->value,
            ]);

            session()->flash('success', 'Crypto Payment Gateway successfully enabled.');

        } else {

            session()->flash('success', 'Crypto Payment Gateway successfully disabled.');

            $cryptoConfig = null;

        }

        User::query()
            ->where('id', auth()->id())
            ->update([
                'crypto_config' => $cryptoConfig,
            ]);

        return redirect()->back();
    }
}
