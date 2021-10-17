<?php

declare(strict_types=1);

namespace App\EventListener\File;

use App\Event\File\NewFileUploadedEvent;
use App\Queue\Message\ProcessUploadedFileMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class FileEventsSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $messageBus;

    /**
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @return iterable
     */
    public static function getSubscribedEvents(): iterable
    {
        yield NewFileUploadedEvent::class => 'onNewFileUploaded';
    }

    /**
     * @param NewFileUploadedEvent $event
     */
    public function onNewFileUploaded(NewFileUploadedEvent $event): void
    {
        foreach ($event->getFiles() as $file) {
            $this->messageBus->dispatch(new ProcessUploadedFileMessage($file->getId()));
        }
    }
}