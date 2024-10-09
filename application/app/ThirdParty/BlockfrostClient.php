<?php

namespace App\ThirdParty;

use Exception;
use App\Enums\CardanoNetwork;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class BlockfrostClient
{
    private const HTTP_REQUEST_GET = 'get';
    private const HTTP_REQUEST_POST = 'post';
    private const HTTP_TIMEOUT_SECONDS = 30;

    private CardanoNetwork $cardanoNetwork;
    private string $apiKey;

    public function __construct(CardanoNetwork $cardanoNetwork, string $apiKey)
    {
        $this->cardanoNetwork = $cardanoNetwork;
        $this->apiKey = $apiKey;
    }

    /**
     * @throws ConnectionException|Exception
     */
    public function get(string $requestUri, array $headers = []): array|null
    {
        return $this->call(self::HTTP_REQUEST_GET, $requestUri, null, $headers);
    }

    /**
     * @throws ConnectionException|Exception
     */
    public function post(string $requestUri, array $payload, array $headers = []): array|null
    {
        return $this->call(self::HTTP_REQUEST_POST, $requestUri, $payload, $headers);
    }

    private function buildEndpoint(string $requestUri): string
    {
        return sprintf(
            'https://cardano-%s.blockfrost.io/api/v0/%s',
            $this->cardanoNetwork === CardanoNetwork::MAINNET ? 'mainnet' : 'preprod',
            $requestUri
        );
    }

    /**
     * @throws ConnectionException|Exception
     */
    private function call(string $requestMethod, string $requestUri, array|null $payload = null, array $headers = []): array|null
    {
        $client = Http::withHeaders(array_merge($headers, [ 'project_id' => $this->apiKey ]))
            ->timeout(self::HTTP_TIMEOUT_SECONDS)
            ->connectTimeout(self::HTTP_TIMEOUT_SECONDS);

        $requestEndpoint = $this->buildEndpoint($requestUri);

        $response = $requestMethod === self::HTTP_REQUEST_GET
            ? $client->get($requestEndpoint)
            : $client->post($requestEndpoint, $payload);

        if ($response->status() === 404) {
            return null;
        }

        if ($response->successful()) {
            return $response->json();
        }

        throw new Exception($response->json('message', 'Unknown error occurred'));
    }
}
