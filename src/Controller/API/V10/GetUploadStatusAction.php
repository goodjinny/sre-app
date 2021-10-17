<?php

declare(strict_types=1);

namespace App\Controller\API\V10;

use App\Entity\FileUpload;
use App\Http\Client\DebrickedApiClient;
use App\Repository\FileUploadRepository;
use App\Traits\EntityManagerTrait;
use App\Traits\SerializerTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * GetUploadStatusAction.
 */
final class GetUploadStatusAction
{
    use EntityManagerTrait;
    use SerializerTrait;

    private DebrickedApiClient $apiClient;

    /**
     * @param DebrickedApiClient $apiClient
     */
    public function __construct(DebrickedApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * @param string $uploadId
     * @param FileUploadRepository $fileUploadRepository
     *
     * @return JsonResponse
     *
     * @Route("/files/upload/{uploadId}/status", name="api_v1.0_get_upload_status", methods={"GET"})
     */
    public function __invoke(string $uploadId, FileUploadRepository $fileUploadRepository): JsonResponse
    {
        $fileUpload = $fileUploadRepository->findUploadByHashId($uploadId);

        if (null === $fileUpload) {
            throw new \InvalidArgumentException('No upload found by given id');
        }

        if (false === $fileUpload->isProcessed()) {
            $this->updateFileUploadStatus($fileUpload);
        }

        return new JsonResponse([
            'processed' => $fileUpload->isProcessed(),
            'status' => $fileUpload->getStatus()
        ]);
    }

    /**
     * Retrieves upload status from Debricked service and saves in to entity
     *
     * @param FileUpload $fileUpload
     */
    private function updateFileUploadStatus(FileUpload $fileUpload): void
    {
        $response = $this->apiClient->getCiUploadStatus($fileUpload->getCiUploadId());

        if (null === $response) {
            return;
        }

        $fileUpload->setIsProcessed($response->isUploadProcessed());
        $fileUpload->setStatus($this->serializer->normalize($response, JsonEncoder::FORMAT));

        $this->em->flush();
    }
}