<?php

declare(strict_types=1);

namespace App\Queue\MessageHandler;

use App\DTO\Debricked\UploadStatusResponseDto;
use App\Http\Client\DebrickedApiClient;
use App\Notification\VulnerabilitiesFoundNotification;
use App\Queue\Message\FileUploadProcessedMessage;
use App\Repository\FileUploadRepository;
use App\Traits\MailerTrait;
use App\Traits\NotifierTrait;
use App\Traits\SerializerTrait;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

final class FileUploadProcessedMessageHandler implements MessageHandlerInterface
{
    use NotifierTrait;
    use MailerTrait;
    use SerializerTrait;

    private FileUploadRepository $fileUploadRepository;
    private DebrickedApiClient $apiClient;

    /**
     * @param FileUploadRepository $fileUploadRepository
     * @param DebrickedApiClient $apiClient
     */
    public function __construct(FileUploadRepository $fileUploadRepository, DebrickedApiClient $apiClient)
    {
        $this->fileUploadRepository = $fileUploadRepository;
        $this->apiClient = $apiClient;
    }

    /**
     * @param FileUploadProcessedMessage $message
     */
    public function __invoke(FileUploadProcessedMessage $message): void
    {
        $fileUpload = $this->fileUploadRepository->findUploadByHashId($message->getUploadHashId());

        if (null === $fileUpload || !$fileUpload->isProcessed()) {
            return;
        }

        $debrickedUser = $this->apiClient->getDebrickedUserInfo();

        if (null === $debrickedUser) {
            return;
        }

        /** @var UploadStatusResponseDto $uploadStatus */
        $uploadStatus = $this->serializer->denormalize($fileUpload->getStatus(), UploadStatusResponseDto::class, JsonEncoder::FORMAT);

        if (!$uploadStatus->isVulnerabilitiesFound() || !$uploadStatus->getSendEmailActions()) {
            return;
        }

        $recipient = new Recipient($debrickedUser->getEmail());

        foreach ($uploadStatus->getSendEmailActions() as $sendEmailAction) {
            $context = [
                'repository_name' => $fileUpload->getRepositoryName(),
                'rule_description' => $sendEmailAction->getRuleDescription(),
                'vulnerabilities' => $uploadStatus->getAllFoundVulnerabilities(),
                'details_url' => $uploadStatus->getDetailsUrl()
            ];

            $notification = (new VulnerabilitiesFoundNotification('Automations Notification', ['email']))
                ->content(sprintf(
                    <<<EOF
                        A recent scan of the repository %s caused the following rule to trigger: %s;
                        
                        %d vulnerabilities found (view details: %s) 
                    EOF,
                    $fileUpload->getRepositoryName(),
                    $sendEmailAction->getRuleDescription(),
                    $uploadStatus->getVulnerabilitiesFound(),
                    $uploadStatus->getDetailsUrl()
                ))
                ->setContext($context)
            ;

            $this->notifier->send($notification, $recipient);
        }
    }
}