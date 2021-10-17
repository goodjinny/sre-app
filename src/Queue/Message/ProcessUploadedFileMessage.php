<?php

declare(strict_types=1);

namespace App\Queue\Message;

/**
 * ProcessUploadedFile.
 */
final class ProcessUploadedFileMessage implements AsyncMessageInterface
{
    private int $fileId;

    /**
     * @param int $fileId
     */
    public function __construct(int $fileId)
    {
        $this->fileId = $fileId;
    }

    /**
     * @return int
     */
    public function getFileId(): int
    {
        return $this->fileId;
    }
}