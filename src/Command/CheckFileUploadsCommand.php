<?php

namespace App\Command;

use App\Entity\FileUpload;
use App\Queue\Message\ProcessUploadedFileMessage;
use App\Repository\FileUploadRepository;
use App\Traits\MessageBusTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:file-upload:check',
    description: 'Checks file upload sessions for not uploaded files and try to upload them',
)]
class CheckFileUploadsCommand extends Command
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

        /** @var FileUpload[] $uncompletedUploads */
        $uncompletedUploads = $this->fileUploadRepository->getUncompletedUploads();

        if (!count($uncompletedUploads)) {
            $io->info('No uncompleted uploads found.');

            return Command::SUCCESS;
        }

        foreach ($uncompletedUploads as $upload) {
            foreach ($upload->getFiles() as $file) {
                if ($file->isProcessed()) {
                    continue;
                }
                $this->messageBus->dispatch(new ProcessUploadedFileMessage($file->getId()));
                $io->info(sprintf('File "%s" for upload #%d was resend to debricked', $file->getFileName(), $upload->getId()));
            }
        }

        $io->success('All uncompleted uploads have been resend to Debricked!');

        return Command::SUCCESS;
    }
}
