<?php

namespace App\Response\Search;

use App\Entity\Book;
use Doctrine\Common\Collections\ArrayCollection;

class IndexResponse
{
    private const ID = 'id';
    private const TITLE = 'title';
    private const ISBN = 'isbn';
    private const PAGE_COUNT = 'page_count';
    private const DATE = 'date';
    private const PRICE_AMOUNT = 'price_amount';
    private const CURRENCY = 'currency';
    private const THUMBNAIL_URL = 'thumbnail_url';
    private const SHORT_DESCRIPTION = 'short_description';
    private const LONG_DESCRIPTION = 'long_description';
    private const STATUS = 'status';
    private const CATEGORIES = 'categories';
    private const AUTHORS = 'authors';

    public static function create(array $data = []): array
    {
        $collection = new ArrayCollection();
        /** @var Book $book */
        foreach ($data as $book) {
            $categories = [];
            foreach ($book->getCategories() as $category) {
                $categories[] = $category->getCategoryName();
            }

            $authors = [];
            foreach ($book->getAuthors() as $author) {
                $authors[] = $author->getAuthorName();
            }

            $collection->add([
                self::ID => $book->getId(),
                self::TITLE => $book->getTitle(),
                self::ISBN => $book->getIsbn(),
                self::PAGE_COUNT => $book->getPageCount(),
                self::PRICE_AMOUNT => $book->getPrice()->getAmount(),
                self::CURRENCY => $book->getPrice()->getCurrency()->getCode(),
                self::DATE => $book->getDate()->format(DATE_ATOM),
                self::THUMBNAIL_URL => $book->getThumbnailUrl(),
                self::SHORT_DESCRIPTION => $book->getShortDescription(),
                self::LONG_DESCRIPTION => $book->getLongDescription(),
                self::STATUS => $book->getStatus(),
                self::CATEGORIES => $categories,
                self::AUTHORS => $authors,
            ]);
        }

        return [
            'total' => $collection->count(),
            'data' => $collection->toArray(),
        ];
    }
}
