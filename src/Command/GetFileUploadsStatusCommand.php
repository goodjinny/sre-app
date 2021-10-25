<?php

namespace App\Command;

use App\Entity\FileUpload;
use App\Queue\Message\CheckUploadStatusMessage;
use App\Repository\FileUploadRepository;
use App\Traits\MessageBusTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:file-upload:status',
    description: 'Checks for unprocessed file-uploads by Debricked and requests status again',
)]
class GetFileUploadsStatusCommand extends Command
{
    use MessageBusTrait;

    private FileUploadRepository $fileUploadRepository;

    /**
     * @param FileUploadRepository $fileUploadRepository
     */
    public function __construct(FileUploadRepository $fileUploadRepository)
    {
        parent::__construct();
        $this->fileUploadRepository = $fileUploadRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->comment('Checking for unprocessed uploads...');

        /** @var FileUpload[] $unprocessedUploads */
        $unprocessedUploads = $this->fileUploadRepository->getUnprocessedUploads();

        if (!count($unprocessedUploads)) {
            $io->info('No unprocessed uploads found.');

            return Command::SUCCESS;
        }

        $io->info(sprintf('%d unprocessed uploads found.', count($unprocessedUploads)));

        foreach ($unprocessedUploads as $upload) {
            $this->messageBus->dispatch(new CheckUploadStatusMessage($upload->getHash()));
            $io->info(sprintf('Check status request for upload (id:%d/hash:%s) have been sent to Debricked', $upload->getId(), $upload->getHash()));
        }

        $io->success('All unprocessed uploads have been processed!');

        return Command::SUCCESS;
    }
}
