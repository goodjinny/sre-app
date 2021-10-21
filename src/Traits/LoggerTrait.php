<?php

declare(strict_types=1);

namespace App\Traits;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Log\Logger;

/**
 * LoggerTrait.
 */
trait LoggerTrait
{
    /** @var LoggerInterface|Logger */
    protected LoggerInterface $logger;

    /**
     * @param LoggerInterface|Logger $logger
     *
     * @required
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @return Logger
     */
    public function getLogger(): Logger
    {
        if (!$this->logger instanceof Logger) {
            throw new \RuntimeException(sprintf('Logger is not instance of %s', Logger::class));
        }

        return $this->logger;
    }
}
