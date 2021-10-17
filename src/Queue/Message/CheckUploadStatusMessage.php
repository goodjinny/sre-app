<?php

declare(strict_types=1);

namespace App\Queue\Message;

/**
 * CheckUploadStatusMessage.
 */
final class CheckUploadStatusMessage
{
    private string $uploadHashId;

    /**
     * @param string $uploadHashId
     */
    public function __construct(string $uploadHashId)
    {
        $this->uploadHashId = $uploadHashId;
    }

    /**
     * @return string
     */
    public function getUploadHashId(): string
    {
        return $this->uploadHashId;
    }
}