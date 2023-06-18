<?php

namespace App\Tests\unit\Response\Search;

use App\Entity\Book;
use App\Entity\BookAuthor;
use App\Entity\BookCategory;
use App\Response\Search\IndexResponse;
use Doctrine\Common\Collections\ArrayCollection;
use Faker\Factory;
use Faker\Generator;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;

class IndexResponseTest extends TestCase
{
    public function testCreateSuccess()
    {
        $faker = Factory::create();
        $book = $this->createMock(Book::class);

        $category = $this->createMock(BookCategory::class);
        $category->expects(static::once())->method('getCategoryName')->willReturn($catName = $faker->word());
        $book->expects(static::once())->method('getCategories')->willReturn(new ArrayCollection([$category]));

        $author = $this->createMock(BookAuthor::class);
        $author->expects(static::once())->method('getAuthorName')->willReturn($authorName = $faker->name());
        $book->expects(static::once())->method('getAuthors')->willReturn(new ArrayCollection([$author]));

        $expectedResponseData = [
            'total' => 1,
            'data' => [
                [
                    'id' => $faker->randomNumber(),
                    'title' => $faker->title(),
                    'isbn' => $faker->isbn10(),
                    'page_count' => $faker->numberBetween(300, 500),
                    'date' => ($date = $faker->dateTimeInInterval('-15 years', '-1 year'))->format(DATE_ATOM),
                    'price_amount' => (string) $faker->randomNumber(),
                    'currency' => $faker->currencyCode(),
                    'thumbnail_url' => $faker->imageUrl(),
                    'short_description' => $faker->text(100),
                    'long_description' => $faker->realTextBetween(150, 500),
                    'status' => 'publish',
                    'categories' => [
                        $catName,
                    ],
                    'authors' => [
                        $authorName,
                    ],
                ],
            ],
        ];

        $money = new Money((int) $expectedResponseData['data'][0]['price_amount'], new Currency($expectedResponseData['data'][0]['currency']));
        $book->expects(static::once())->method('getId')->willReturn($expectedResponseData['data'][0]['id']);
        $book->expects(static::once())->method('getTitle')->willReturn($expectedResponseData['data'][0]['title']);
        $book->expects(static::once())->method('getIsbn')->willReturn($expectedResponseData['data'][0]['isbn']);
        $book->expects(static::once())->method('getPageCount')->willReturn($expectedResponseData['data'][0]['page_count']);
        $book->expects(static::once())->method('getDate')->willReturn($date);
        $book->expects(static::exactly(2))->method('getPrice')->willReturnOnConsecutiveCalls($money, $money);
        $book->expects(static::once())->method('getThumbnailUrl')->willReturn($expectedResponseData['data'][0]['thumbnail_url']);
        $book->expects(static::once())->method('getShortDescription')->willReturn($expectedResponseData['data'][0]['short_description']);
        $book->expects(static::once())->method('getLongDescription')->willReturn($expectedResponseData['data'][0]['long_description']);
        $book->expects(static::once())->method('getStatus')->willReturn($expectedResponseData['data'][0]['status']);

        $this->assertEquals($expectedResponseData, IndexResponse::create([$book]));
    }
}