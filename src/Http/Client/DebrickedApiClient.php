<?php

declare(strict_types=1);

namespace App\Http\Client;

/**
 * DebrickedApiClient.
 */
final class DebrickedApiClient
{
    private DebrickedHttpClient $httpClient;

    /**
     * @param DebrickedHttpClient $httpClient
     */
    public function __construct(DebrickedHttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return array
     */
    public function getSupportedDependencies(): array
    {
        $response = $this->httpClient->request('GET', '1.0/open/supported/dependency/files');

        return $response->toArray();
    }
}