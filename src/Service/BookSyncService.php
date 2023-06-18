<?php

namespace App\Service;

use App\Component\Mapper\BookMapper;
use App\Entity\Book;
use App\Entity\BookAuthor;
use App\Entity\BookCategory;
use App\Entity\BookRelationInterface;
use App\Exception\ClientException;
use App\Repository\BookAuthorRepository;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class BookSyncService
{
    private const PATH_URI = '/d7f02fdc-5591-4080-a163-95a08ce6895e';

    public function __construct(
        private BookClient      $bookClient,
        private BookRepository  $bookRepository,
        private BookCategoryRepository $bookCategoryRepository,
        private BookAuthorRepository $bookAuthorRepository,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
    )
    {

    }

    public function sync(): void
    {
        try {
            $response = $this->bookClient->get(self::PATH_URI);
        } catch (ClientException $exception) {
            $this->logger->error(sprintf(get_class($this) . ' error. Message : %s --- trace: %s', $exception->getMessage(), $exception->getTraceAsString()));

            throw $exception;
        }

        $bookResponse = json_decode($response->getBody()->getContents(), true);

        // We can use pure SQL in case the data amount is huge
        $existsBooks = $this->bookRepository->findAll();
        $existsCategories = $this->bookCategoryRepository->findAll();
        $existsAuthors = $this->bookAuthorRepository->findAll();
        foreach ($bookResponse as $book) {
            $dbBook = $this->searchInExistsBook($existsBooks, $book);
            if ($dbBook) {
                $bookEntity = BookMapper::map($dbBook, $book);
            } else {
                $bookEntity = BookMapper::map(new Book(), $book);
            }

            $bookEntity->removeCategories();
            foreach ($book['categories'] as $category) {
                $dbCategory = $this->searchInExistsDbForRelations($category, 'getCategoryName', ...$existsCategories);
                if (!$dbCategory) {
                    $dbCategory = new BookCategory();
                    $dbCategory->setCategoryName($category);
                    $this->bookCategoryRepository->save($dbCategory);
                }
                $bookEntity->addCategory($dbCategory);
            }

            $bookEntity->removeAuthors();
            foreach ($book['authors'] as $author) {
                $dbAuthor = $this->searchInExistsDbForRelations($author, 'getAuthorName', ...$existsAuthors);
                if (!$dbAuthor) {
                    $dbAuthor = new BookAuthor();
                    $dbAuthor->setAuthorName($author);
                    $this->bookAuthorRepository->save($dbAuthor);
                }
                $bookEntity->addAuthor($dbAuthor);
            }

            $this->bookRepository->save($bookEntity);
        }

        // Clean the cache
        $this->entityManager->getConfiguration()->getResultCache()->clear();
    }

    private function searchInExistsBook(array $existsBooks, array $book): ?Book
    {
        $dbBook = null;

        /** @var Book $bookEntity */
        foreach ($existsBooks as $bookEntity) {
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

    private function searchInExistsDbForRelations(string $field, string $getterMethod, BookRelationInterface ...$entities): ?BookRelationInterface
    {
        foreach ($entities as $entity) {
            if ($entity->$getterMethod() == $field) {
                return $entity;
            }
        }

        return null;
    }
}