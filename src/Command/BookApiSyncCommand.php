<?php

namespace App\Command;

use App\Exception\ClientException;
use App\Service\BookSyncService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'consumer:sync-books')]
class BookApiSyncCommand extends Command
{
    public function __construct(
        private BookSyncService $bookSyncService,
        private LoggerInterface $logger,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Fetching latest data from book API.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $this->bookSyncService->sync();
        } catch (ClientException $e) {
            $io->error('Books are not populated due to Book API error.');
            $this->logger->error(sprintf('consumer:sync-books command error. Message : % --- trace: %s', $e->getMessage(), $e->getTraceAsString()));
        } catch (\RuntimeException $e) {
            $io->error('Books are not populated due to some reason.');
            $this->logger->error(sprintf('consumer:sync-books command error. Message : % --- trace: %s', $e->getMessage(), $e->getTraceAsString()));
        }

        return 0;
    }
}