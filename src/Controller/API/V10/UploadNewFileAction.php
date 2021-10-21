<?php

declare(strict_types=1);

namespace App\Controller\API\V10;

use App\Entity\File;
use App\Entity\FileUpload;
use App\Event\File\NewFileUploadEvent;
use App\Service\FileStorageService;
use App\Traits\EntityManagerTrait;
use App\Traits\EventDispatcherTrait;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * UploadNewFileAction.
 */
final class UploadNewFileAction
{
    use EntityManagerTrait;
    use EventDispatcherTrait;

    /**
     * @param Request $request
     * @param FileStorageService $fileStorage
     *
     * @return JsonResponse
     *
     * @Route("/files", name="api_v1.0_upload_new_file", methods={"POST"})
     */
    public function __invoke(Request $request, FileStorageService $fileStorage): JsonResponse
    {
        $fileEntities = [];

        /** @var UploadedFile[] $files */
        $files = $request->files->get('files');
        $params = $request->request->all();

        $fileUpload = new FileUpload($params['repositoryName'], $params['commitName']);

        foreach ($files as $file) {
            $fileEntity = new File();
            $fileEntity->setFileName($file->getClientOriginalName());
            $fileEntity->setFileUpload($fileUpload);

            $fileStorage->saveUploadedFile($file, $fileUpload->getHash());
            $this->em->persist($fileEntity);
            $fileEntities[] = $fileEntity;
        }

        $this->em->persist($fileUpload);
        $this->em->flush();

        $this->eventDispatcher->dispatch(new NewFileUploadEvent($fileEntities));

        return new JsonResponse([
            'uploadId' => $fileUpload->getHash()
        ]);
    }
}