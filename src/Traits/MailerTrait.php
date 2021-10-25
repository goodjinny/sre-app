<?php

declare(strict_types=1);

namespace App\Traits;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;

/**
 * MailerTrait.
 */
trait MailerTrait
{
    /** @var MailerInterface|Mailer */
    protected MailerInterface $mailer;

    /**
     * @param MailerInterface|Mailer $mailer
     *
     * @required
     */
    public function setMailer(MailerInterface $mailer): void
    {
        $this->mailer = $mailer;
    }

    /**
     * @return Mailer
     */
    public function getMailer(): Mailer
    {
        if (!$this->mailer instanceof Mailer) {
            throw new \RuntimeException(sprintf('Mailer is not instance of %s', Mailer::class));
        }

        return $this->mailer;
    }
}
