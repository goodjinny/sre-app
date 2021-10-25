<?php

declare(strict_types=1);

namespace App\Http\Client;

use App\DTO\Debricked\DebrickedUserDto;
use App\DTO\Debricked\UploadFileResponseDto;
use App\DTO\Debricked\UploadStatusResponseDto;
use App\Traits\SerializerTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * DebrickedApiClient.
 */
final class DebrickedApiClient
{
    use SerializerTrait;

    private DebrickedHttpClient $httpClient;

    /**
     * @param DebrickedHttpClient $httpClient
     */
    public function __construct(DebrickedHttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Requests supported dependencies files formats
     *
     * @return array
     */
    public function getSupportedDependencies(): array
    {
        $response = $this->httpClient->request('GET', '1.0/open/supported/dependency/files');

        return $response->toArray();
    }

    /**
     * Uploads dependency file for future processing
     *
     * @param string $filePath
     * @param string $repositoryName
     * @param string $commitName
     * @param int|null $ciUploadId
     *
     * @return UploadFileResponseDto|null
     */
    public function uploadDependencyFile(string $filePath, string $repositoryName, string $commitName, ?int $ciUploadId = null): ?UploadFileResponseDto
    {
        $data = [
            'fileData' => DataPart::fromPath($filePath),
            'repositoryName' => $repositoryName,
            'commitName' => $commitName,
        ];

        if (null !== $ciUploadId) {
            $data['ciUploadId'] = (string) $ciUploadId;
        }

        $formData = new FormDataPart($data);

        $response = $this->httpClient->request('POST', '1.0/open/uploads/dependencies/files', [
                'headers' => $formData->getPreparedHeaders()->toArray(),
                'body' => $formData->bodyToIterable(),
            ]
        );

        return Response::HTTP_OK === $response->getStatusCode()
            ? $this->serializer->deserialize($response->getContent(), UploadFileResponseDto::class, JsonEncoder::FORMAT)
            : null;
    }

    /**
     * Concludes files upload for given upload session
     *
     * @param int $ciUploadId
     *
     * @return bool
     */
    public function concludeFilesUpload(int $ciUploadId): bool
    {
        $data = [
            'ciUploadId' => (string) $ciUploadId,
        ];

        $formData = new FormDataPart($data);

        $response = $this->httpClient->request('POST', '1.0/open/finishes/dependencies/files/uploads', [
                'headers' => $formData->getPreparedHeaders()->toArray(),
                'body' => $formData->bodyToIterable(),
            ]
        );

        return Response::HTTP_NO_CONTENT === $response->getStatusCode();
    }

    /**
     * Requests upload status for given upload session
     *
     * @param int $ciUploadId
     *
     * @return UploadStatusResponseDto|null
     */
    public function getCiUploadStatus(int $ciUploadId): ?UploadStatusResponseDto
    {
        $response = $this->httpClient->request('GET', '1.0/open/ci/upload/status', [
            'query' => [
                'ciUploadId' => $ciUploadId
            ]
        ]);

        return Response::HTTP_OK === $response->getStatusCode()
            ? $this->serializer->deserialize($response->getContent(), UploadStatusResponseDto::class, JsonEncoder::FORMAT)
            : null;
    }

    /**
     * Requests current user information
     *
     * @return DebrickedUserDto|null
     */
    public function getDebrickedUserInfo(): ?DebrickedUserDto
    {
        $response = $this->httpClient->request('GET', '1.0/open/zapier/user');

        return Response::HTTP_OK === $response->getStatusCode()
            ? $this->serializer->deserialize($response->getContent(), DebrickedUserDto::class, JsonEncoder::FORMAT)
            : null;
    }
}