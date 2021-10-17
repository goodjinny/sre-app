<?php

declare(strict_types=1);

namespace App\Queue\MessageHandler;

use App\Entity\File;
use App\Http\Client\DebrickedApiClient;
use App\Queue\Message\ProcessUploadedFileMessage;
use App\Repository\FileRepository;
use App\Service\FileStorageService;
use App\Traits\EntityManagerTrait;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class ProcessUploadedFileMessageHandler implements MessageHandlerInterface
{
    use EntityManagerTrait;

    private FileRepository $fileRepository;
    private DebrickedApiClient $debrickedApiClient;
    private FileStorageService $fileStorage;

    /**
     * @param FileRepository $fileRepository
     * @param DebrickedApiClient $debrickedApiClient
     * @param FileStorageService $fileStorage
     */
    public function __construct(FileRepository $fileRepository, DebrickedApiClient $debrickedApiClient, FileStorageService $fileStorage)
    {
        $this->fileRepository = $fileRepository;
        $this->debrickedApiClient = $debrickedApiClient;
        $this->fileStorage = $fileStorage;
    }

    public function __invoke(ProcessUploadedFileMessage $message)
    {
        /** @var File $file */
        $file = $this->fileRepository->find($message->getFileId());
        $fileUpload = $file->getFileUpload();

        $response = $this->debrickedApiClient->uploadDependencyFile(
            $this->fileStorage->getFileEntityPath($file),
            $fileUpload->getRepositoryName(),
            $fileUpload->getCommitName(),
            $fileUpload->getCiUploadId()
        );

        if (null === $response) {
            return;
        }

        $fileUpload->setCiUploadId($response->getCiUploadId());
        $file->setIsProcessed(true);

        $this->em->flush();
        $this->em->refresh($fileUpload);

        $this->fileStorage->removeUploadedFile($file);

        if ($fileUpload->allFilesAreProcessed()) {
            $this->debrickedApiClient->concludeFilesUpload($fileUpload->getCiUploadId());
        }
    }
}