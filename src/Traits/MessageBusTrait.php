<?php

declare(strict_types=1);

namespace App\Traits;

use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * MessageBusTrait.
 */
trait MessageBusTrait
{
    /** @var MessageBusInterface|MessageBus */
    protected MessageBusInterface $messageBus;

    /**
     * @param MessageBusInterface|MessageBus $messageBus
     *
     * @required
     */
    public function setMessageBus(MessageBusInterface $messageBus): void
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @return MessageBus
     */
    public function getMessageBus(): MessageBus
    {
        if (!$this->messageBus instanceof MessageBus) {
            throw new \RuntimeException(sprintf('MessageBus is not instance of %s', MessageBus::class));
        }

        return $this->messageBus;
    }
}
