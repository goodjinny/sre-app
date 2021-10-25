<?php

declare(strict_types=1);

namespace App\Notification;

use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;

/**
 * VulnerabilitiesFoundNotification.
 */
final class VulnerabilitiesFoundNotification extends Notification implements EmailNotificationInterface
{
    private array $context = [];

    public function asEmailMessage(EmailRecipientInterface $recipient, string $transport = null): ?EmailMessage
    {
        if (!class_exists(NotificationEmail::class)) {
            throw new \LogicException(sprintf('The "%s" method requires "symfony/twig-bridge:>4.4".', __METHOD__));
        }

        $email = NotificationEmail::asPublicEmail()
            ->to($recipient->getEmail())
            ->subject($this->getSubject())
            ->content(  $this->getContent())
            ->htmlTemplate('email/vulnerabilities_found_notification.html.twig')
            ->context($this->getContext())
        ;

        return new EmailMessage($email);
    }

    /**
     * @param array $context
     *
     * @return self
     */
    public function setContext(array $context): self
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @return array
     */
    private function getContext(): array
    {
        return $this->context;
    }
}
