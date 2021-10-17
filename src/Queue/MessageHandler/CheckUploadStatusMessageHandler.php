<?php

declare(strict_types=1);

namespace App\Queue\MessageHandler;

use App\Http\Client\DebrickedApiClient;
use App\Queue\Message\CheckUploadStatusMessage;
use App\Repository\FileUploadRepository;
use App\Traits\EntityManagerTrait;
use App\Traits\SerializerTrait;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

final class CheckUploadStatusMessageHandler implements MessageHandlerInterface
{
    use SerializerTrait;
    use EntityManagerTrait;

    private FileUploadRepository $fileUploadRepository;
    private DebrickedApiClient $apiClient;

    /**
     * @param FileUploadRepository $fileUploadRepository
     * @param DebrickedApiClient $apiClient
     */
    public function __construct(FileUploadRepository $fileUploadRepository, DebrickedApiClient $apiClient)
    {
        $this->fileUploadRepository = $fileUploadRepository;
        $this->apiClient = $apiClient;
    }

    /**
     * @param CheckUploadStatusMessage $message
     */
    public function __invoke(CheckUploadStatusMessage $message): void
    {
        $fileUpload = $this->fileUploadRepository->findUploadByHashId($message->getUploadHashId());

        if (null === $fileUpload || $fileUpload->isProcessed()) {
            return;
        }

        $response = $this->apiClient->getCiUploadStatus($fileUpload->getCiUploadId());

        if (null === $response) {
            return;
        }

        $fileUpload->setIsProcessed($response->isUploadProcessed());
        $fileUpload->setStatus($this->serializer->normalize($response, JsonEncoder::FORMAT));

        $this->em->flush();
    }
}