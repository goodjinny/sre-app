<?php

declare(strict_types=1);

namespace App\Controller\API\V10;

use App\Http\Client\DebrickedApiClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * GetSupportedDependenciesListAction.
 */
final class GetSupportedDependenciesListAction
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/info/supported-dependencies", name="api_get_supported_dependencies")
     */
    public function __invoke(Request $request, DebrickedApiClient $httpClient): JsonResponse
    {
        $data = $httpClient->getSupportedDependencies();

        return new JsonResponse($data);
    }
}