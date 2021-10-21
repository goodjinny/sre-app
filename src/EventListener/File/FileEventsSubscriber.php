<?php

declare(strict_types=1);

namespace App\EventListener\File;

use App\Event\File\NewFileUploadEvent;
use App\Queue\Message\ProcessUploadedFileMessage;
use App\Traits\MessageBusTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class FileEventsSubscriber implements EventSubscriberInterface
{
    use MessageBusTrait;

    /**
     * @return iterable
     */
    public static function getSubscribedEvents(): iterable
    {
        yield NewFileUploadEvent::class => 'onNewFileUpload';
    }

    /**
     * @param NewFileUploadEvent $event
     */
    public function onNewFileUpload(NewFileUploadEvent $event): void
    {
        foreach ($event->getFiles() as $file) {
            $this->messageBus->dispatch(new ProcessUploadedFileMessage($file->getId()));
        }
    }
}