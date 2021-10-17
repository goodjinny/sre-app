<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\File as FileEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class FileStorageService
{
    private string $fileStoragePath;

    /**
     * @param string $fileStoragePath
     */
    public function __construct(string $fileStoragePath)
    {
        $this->fileStoragePath = $fileStoragePath;
    }

    /**
     * Saves uploaded file to current upload directory
     *
     * @param UploadedFile $file
     * @param string $directory
     *
     * @return File
     */
    public function saveUploadedFile(UploadedFile $file, string $directory): File
    {
        return $file->move(
            implode('/', [$this->fileStoragePath, $directory]),
            $file->getClientOriginalName()
        );
    }

    /**
     * Removes Entity's file from upload session directory. Removes directory if it's empty.
     *
     * @param FileEntity $file
     *
     * @return bool
     */
    public function removeUploadedFile(FileEntity $file): bool
    {
        $removed = unlink($this->getFileEntityPath($file));

        $directoryPath = implode('/', [$this->fileStoragePath, $file->getFileUpload()->getHash()]);

        $fi = new \FilesystemIterator($directoryPath);

        if (!iterator_count($fi)) {
            rmdir($directoryPath);
        }

        return $removed;
    }

    /**
     * Returns full path for given file in upload directory
     *
     * @param string $fileName
     * @param string $directory
     *
     * @return string
     */
    public function getFilePath(string $fileName, string $directory = ''): string
    {
        return implode('/', [$this->fileStoragePath, $directory, $fileName]);
    }

    /**
     * Returns File entity file path
     *
     * @param FileEntity $file
     *
     * @return string
     */
    public function getFileEntityPath(FileEntity $file): string
    {
        return implode('/', [$this->fileStoragePath, $file->getFileUpload()->getHash(), $file->getFileName()]);
    }
}