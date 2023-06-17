<?php

namespace App\Component\Mapper;

use App\Entity\Book;
use App\Entity\BookCategory;
use Money\Currency;
use Money\Money;

class BookMapper
{
    public static function map(Book $bookEntity, array $bookApiResponse): Book
    {
        $bookEntity->setTitle($bookApiResponse['title']);
        $bookEntity->setIsbn($bookApiResponse['isbn'] ?? null);
        $bookEntity->setPageCount($bookApiResponse['pageCount']);
        $bookEntity->setPrice(new Money($bookApiResponse['published']['price'], new Currency($bookApiResponse['published']['currency'])));
        $bookEntity->setThumbnailUrl($bookApiResponse['thumbnailUrl'] ?? null);
        $bookEntity->setShortDescription($bookApiResponse['shortDescription'] ?? null);
        $bookEntity->setLongDescription($bookApiResponse['longDescription'] ?? null);
        $bookEntity->setStatus($bookApiResponse['status']);
        $bookEntity->setDate(new \DateTime($bookApiResponse['published']['$date']));

        return $bookEntity;
    }
}