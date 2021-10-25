<?php

declare(strict_types=1);

namespace App\Traits;

use Symfony\Component\Notifier\Notifier;
use Symfony\Component\Notifier\NotifierInterface;

/**
 * NotifierTrait.
 */
trait NotifierTrait
{
    /** @var NotifierInterface|Notifier */
    protected NotifierInterface $notifier;

    /**
     * @param NotifierInterface|Notifier $notifier
     *
     * @required
     */
    public function setNotifier(NotifierInterface $notifier): void
    {
        $this->notifier = $notifier;
    }

    /**
     * @return Notifier
     */
    public function getNotifier(): Notifier
    {
        if (!$this->notifier instanceof Notifier) {
            throw new \RuntimeException(sprintf('Notifier is not instance of %s', Notifier::class));
        }

        return $this->notifier;
    }
}
