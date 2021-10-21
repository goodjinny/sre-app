<?php

declare(strict_types=1);

namespace App\Event\File;

use App\Entity\File;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * NewFileUploadedEvent.
 */
final class NewFileUploadEvent extends Event
{
    /**
     * @var File[]|array
     */
    private array $files;

    /**
     * @param File[]|array $files
     */
    public function __construct(array $files)
    {
        $this->files = $files;
    }

    /**
     * @return File[]|array
     */
    public function getFiles(): array
    {
        return $this->files;
    }
}