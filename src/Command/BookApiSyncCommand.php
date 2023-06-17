<?php

namespace App\Command;

use App\Component\Mapper\BookMapper;
use App\Entity\Book;
use App\Exception\ClientException;
use App\Repository\BookRepository;
use App\Service\BookClient;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BookApiSyncCommand extends Command
{
    protected static $defaultName = 'consumer:sync-books';

    private const PATH_URI = '/d7f02fdc-5591-4080-a163-95a08ce6895e';

    public function __construct(
        private BookClient      $bookClient,
        private BookRepository  $bookRepository,
        private LoggerInterface $logger,
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Fetching latest data from book API.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $response = $this->bookClient->get(self::PATH_URI);
        } catch (ClientException $exception) {
            $io->error('Books are not populated due to Book API error.');
            $this->logger->error(sprintf('consumer:book-api command error. Message : % --- trace: %s', $exception->getMessage(), $exception->getTraceAsString()));

            throw $exception;
        }

        $bookResponse = json_decode($response->getBody()->getContents(), true);

        // We can use pure SQL in case the data amount is huge
        $booksInDb = $this->bookRepository->findAll();
        foreach ($bookResponse as $book) {
            $dbBook = $this->searchInDb($booksInDb, $book);
            if ($dbBook) {
                $bookEntity = BookMapper::map($dbBook, $book);
            } else {
                $bookEntity = BookMapper::map(new Book(), $book);
            }

            $this->bookRepository->save($bookEntity);
        }

        return 0;
    }

    private function searchInDb(array $booksInDb, array $book): ?Book
    {
        $dbBook = null;

        /** @var Book $bookEntity */
        foreach ($booksInDb as $bookEntity) {
            if (isset($book['isbn']) && $book['isbn'] !== null && $bookEntity->getIsbn() === $book['isbn']) {
                $dbBook = $bookEntity;
                break;
            }

            if (isset($book['title']) && $book['title'] != null && $bookEntity->getTitle() === $book['title']) {
                $dbBook = $bookEntity;
                break;
            }
        }

        return $dbBook;
    }
}