<?php

declare(strict_types=1);

namespace App\Http\Client;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

/**
 * DebrickedHttpClient.
 */
final class DebrickedHttpClient implements HttpClientInterface
{
    private const SESSION_JWT_TOKEN = 'jwtToken';

    private HttpClientInterface $debrickedClient;
    private ParameterBagInterface $parameterBag;
    private SessionInterface $session;

    /**
     * @param HttpClientInterface $debrickedClient
     * @param ParameterBagInterface $parameterBag
     * @param RequestStack $requestStack
     */
    public function __construct(HttpClientInterface $debrickedClient, ParameterBagInterface $parameterBag, RequestStack $requestStack)
    {
        $this->debrickedClient = $debrickedClient;
        $this->parameterBag = $parameterBag;
        $this->session = $requestStack->getSession();
    }

    /**
     * {@inheritdoc}
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        $response = $this->debrickedClient->request($method, $url, array_merge($options, ['auth_bearer' => $this->session->get(self::SESSION_JWT_TOKEN)]));

        if (Response::HTTP_UNAUTHORIZED === $response->getStatusCode()) {
            return $this->makeAuthorizedRequest($method, $url, $options);
        }

        return $response;
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
     * @param string $method
     * @param string $url
     * @param array $options
     *
     * @return ResponseInterface
     *
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    private function makeAuthorizedRequest(string $method, string $url, array $options): ResponseInterface
    {
        $this->session->get(self::SESSION_JWT_TOKEN)
            ? $this->refreshTokenWithAccessToken()
            : $this->obtainTokenWithAccountCredentials()
        ;

        return $this->debrickedClient->request($method, $url, array_merge($options, ['auth_bearer' => $this->session->get(self::SESSION_JWT_TOKEN)]));
    }

    /**
     * Obtains JWT token with debricked account credentials and saves it to session
     */
    private function obtainTokenWithAccountCredentials(): void
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
            $this->session->set(self::SESSION_JWT_TOKEN, $tokens['token']);
        }
    }

    /**
     * Refreshes JWT token with debricked access token and saves it to session
     */
    private function refreshTokenWithAccessToken(): void
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
            $this->session->set(self::SESSION_JWT_TOKEN, $tokens['token']);
        }
    }
}