<?php

declare(strict_types=1);

namespace App\Http\Client;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

/**
 * DebrickedHttpClient.
 */
final class DebrickedHttpClient implements HttpClientInterface
{
    private const CACHE_KEY_JWT_TOKEN = 'jwt_token';
    private const CACHE_KEY_IS_AUTHORIZED = 'is_authorized';

    private HttpClientInterface $debrickedClient;
    private ParameterBagInterface $parameterBag;
    private CacheInterface $cache;

    /**
     * @param HttpClientInterface $debrickedClient
     * @param ParameterBagInterface $parameterBag
     * @param CacheInterface $cache
     */
    public function __construct(HttpClientInterface $debrickedClient, ParameterBagInterface $parameterBag, CacheInterface $cache)
    {
        $this->debrickedClient = $debrickedClient;
        $this->parameterBag = $parameterBag;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        return $this->debrickedClient->request($method, $url, array_merge($options, ['auth_bearer' => $this->getJwtToken()]));
    }

    /**
     * {@inheritdoc}
     */
    public function stream($responses, float $timeout = null): ResponseStreamInterface
    {
        return $this->debrickedClient->stream($responses, $timeout);
    }

    /**
     * {@inheritdoc}
     */
    public function withOptions(array $options): self
    {
        $clone = clone $this;
        $clone->debrickedClient = $this->debrickedClient->withOptions($options);

        return $clone;
    }

    /**
     * Obtains JWT token with debricked account credentials
     *
     * @return string|null
     */
    private function obtainTokenWithAccountCredentials(): ?string
    {
        $data = [
            '_username' => $this->parameterBag->get('debricked_username'),
            '_password' => $this->parameterBag->get('debricked_password'),
        ];

        $formData = new FormDataPart($data);

        $response = $this->debrickedClient->request('POST', 'login_check', [
                'headers' => $formData->getPreparedHeaders()->toArray(),
                'body' => $formData->bodyToIterable(),
            ]
        );

        if (Response::HTTP_OK === $response->getStatusCode()) {
            $tokens = $response->toArray();

            return $tokens['token'];
        }

        return null;
    }

    /**
     * Refreshes JWT token with debricked access-token
     *
     * @return string|null
     */
    private function refreshTokenWithAccessToken(): ?string
    {
        $data = [
            'refresh_token' => $this->parameterBag->get('debricked_access_token'),
        ];

        $formData = new FormDataPart($data);

        $response = $this->debrickedClient->request('POST', 'login_refresh', [
                'headers' => $formData->getPreparedHeaders()->toArray(),
                'body' => $formData->bodyToIterable(),
            ]
        );

        if (Response::HTTP_OK === $response->getStatusCode()) {
            $tokens = $response->toArray();

            return $tokens['token'];
        }

        return null;
    }

    /**
     * Returns cached JWT token. If token doesn't exist, obtains token with authorization/refresh requests
     *
     * @return string
     */
    private function getJwtToken(): string
    {
        return $this->cache->get(self::CACHE_KEY_JWT_TOKEN, function (ItemInterface $item) {
            $item->expiresAfter(3600);

            $isAuthorizedItem = $this->cache->getItem(self::CACHE_KEY_IS_AUTHORIZED);

            if ($isAuthorizedItem->isHit()) {
                return $this->refreshTokenWithAccessToken();
            }

            $token = $this->obtainTokenWithAccountCredentials();

            if (null !== $token) {
                $isAuthorizedItem->set(true);
                $this->cache->save($isAuthorizedItem);
            }

            return $token;
        });
    }
}