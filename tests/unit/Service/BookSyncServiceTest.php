<?php

namespace App\Tests\unit\Service;

use App\Entity\Book;
use App\Entity\BookAuthor;
use App\Entity\BookCategory;
use App\Exception\ClientException;
use App\Repository\BookAuthorRepository;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Service\BookClient;
use App\Service\BookSyncService;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LoggerInterface;

class BookSyncServiceTest extends TestCase
{
    private BookClient|MockObject      $bookClient;
    private BookRepository|MockObject  $bookRepository;
    private BookCategoryRepository|MockObject $bookCategoryRepository;
    private BookAuthorRepository|MockObject $bookAuthorRepository;
    private EntityManagerInterface|MockObject $entityManager;
    private LoggerInterface|MockObject $logger;

    private BookSyncService $service;

    public function setUp(): void
    {
        $this->bookClient = $this->createMock(BookClient::class);
        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $this->bookAuthorRepository = $this->createMock(BookAuthorRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->service = new BookSyncService(
            $this->bookClient,
            $this->bookRepository,
            $this->bookCategoryRepository,
            $this->bookAuthorRepository,
            $this->entityManager,
            $this->logger,
        );
    }

    public function testClientErrorFailed()
    {
        $this->expectException(ClientException::class);

        $this->bookClient->expects(static::once())
            ->method('get')
            ->willThrowException(new ClientException);

        $this->logger->expects(static::once())
            ->method('error');

        $this->service->sync();
    }

    public function testSuccessSync()
    {
        $faker = Factory::create();
        $this->bookClient->expects(static::once())
            ->method('get')
            ->willReturn($response = $this->createMock(ResponseInterface::class));

        $response->expects(static::once())->method('getBody')->willReturn($body = $this->createMock(StreamInterface::class));
        $body->expects(static::once())->method('getContents')->willReturn($this->getResponseContent());

        $book = new Book();
        $book->setIsbn($faker->isbn10());
        $book->setTitle($faker->word());
        $this->bookRepository->expects(static::once())->method('findAll')->willReturn([$book]);
        $this->bookRepository->expects(static::exactly(5))->method('save');

        $bookCategory = new BookCategory();
        $bookCategory->setCategoryName($faker->word());
        $this->bookCategoryRepository->expects(static::once())->method('findAll')->willReturn([$bookCategory]);
        $this->bookCategoryRepository->expects(static::exactly(6))->method('save');

        $bookAuthor = new BookAuthor();
        $bookAuthor->setAuthorName($faker->name());
        $this->bookAuthorRepository->expects(static::once())->method('findAll')->willReturn([$bookAuthor]);
        $this->bookAuthorRepository->expects(static::exactly(12))->method('save');

        $this->entityManager->expects(static::once())->method('getConfiguration')->willReturn($emConfig = $this->createMock(Configuration::class));
        $emConfig->expects(static::once())->method('getResultCache')->willReturn($pool = $this->createMock(CacheItemPoolInterface::class));
        $pool->expects(static::once())->method('clear');

        $this->service->sync();
    }

    private function getResponseContent(): string
    {
        return '[{
        "title": "Unlocking Android",
        "isbn": "1933988673",
        "pageCount": 416,
        "published": {
            "$date": "2009-04-01T00:00:00.000-0700",
            "price": 40,
            "currency": "USD"
        },
        "thumbnailUrl": "https://s3.amazonaws.com/AKIAJC5RLADLUMVRPFDQ.book-thumb-images/ableson.jpg",
        "shortDescription": "Unlocking Android: A Developer\'s Guide provides concise, hands-on instruction for the Android operating system and development tools. This book teaches important architectural concepts in a straightforward writing style and builds on this with practical and useful examples throughout.",
        "longDescription": "Android is an open source mobile phone platform based on the Linux operating system and developed by the Open Handset Alliance, a consortium of over 30 hardware, software and telecom companies that focus on open standards for mobile devices. Led by search giant, Google, Android is designed to deliver a better and more open and cost effective mobile experience.    Unlocking Android: A Developer\'s Guide provides concise, hands-on instruction for the Android operating system and development tools. This book teaches important architectural concepts in a straightforward writing style and builds on this with practical and useful examples throughout. Based on his mobile development experience and his deep knowledge of the arcane Android technical documentation, the author conveys the know-how you need to develop practical applications that build upon or replace any of Androids features, however small.    Unlocking Android: A Developer\'s Guide prepares the reader to embrace the platform in easy-to-understand language and builds on this foundation with re-usable Java code examples. It is ideal for corporate and hobbyists alike who have an interest, or a mandate, to deliver software functionality for cell phones.    WHAT\'S INSIDE:        * Android\'s place in the market      * Using the Eclipse environment for Android development      * The Intents - how and why they are used      * Application classes:            o Activity            o Service            o IntentReceiver       * User interface design      * Using the ContentProvider to manage data      * Persisting data with the SQLite database      * Networking examples      * Telephony applications      * Notification methods      * OpenGL, animation & multimedia      * Sample Applications  ",
        "status": "PUBLISH",
        "authors": ["W. Frank Ableson", "Charlie Collins", "Robi Sen"],
        "categories": ["Open Source", "Mobile"]
    },
    {
        "title": "Android in Action, Second Edition",
        "isbn": "1935182722",
        "pageCount": 592,
        "published": {
            "$date": "2011-01-14T00:00:00.000-0800",
            "price": 50,
            "currency": "USD"
        },
        "thumbnailUrl": "https://s3.amazonaws.com/AKIAJC5RLADLUMVRPFDQ.book-thumb-images/ableson2.jpg",
        "shortDescription": "Android in Action, Second Edition is a comprehensive tutorial for Android developers. Taking you far beyond \"Hello Android,\" this fast-paced book puts you in the driver\'s seat as you learn important architectural concepts and implementation strategies. You\'ll master the SDK, build WebKit apps using HTML 5, and even learn to extend or replace Android\'s built-in features by building useful and intriguing examples. ",
        "longDescription": "When it comes to mobile apps, Android can do almost anything   and with this book, so can you! Android runs on mobile devices ranging from smart phones to tablets to countless special-purpose gadgets. It\'s the broadest mobile platform available.    Android in Action, Second Edition is a comprehensive tutorial for Android developers. Taking you far beyond \"Hello Android,\" this fast-paced book puts you in the driver\'s seat as you learn important architectural concepts and implementation strategies. You\'ll master the SDK, build WebKit apps using HTML 5, and even learn to extend or replace Android\'s built-in features by building useful and intriguing examples. ",
        "status": "PUBLISH",
        "authors": ["W. Frank Ableson", "Robi Sen"],
        "categories": ["Java"]
    },
    {
        "title": "Specification by Example",
        "isbn": "1617290084",
        "pageCount": 0,
        "published": {
            "$date": "2011-06-03T00:00:00.000-0700",
            "price": 10,
            "currency": "USD"
        },
        "thumbnailUrl": "https://s3.amazonaws.com/AKIAJC5RLADLUMVRPFDQ.book-thumb-images/adzic.jpg",
        "status": "PUBLISH",
        "authors": ["Gojko Adzic"],
        "categories": ["Software Engineering"]
    },
    {
        "title": "Flex 3 in Action",
        "isbn": "1933988746",
        "pageCount": 576,
        "published": {
            "$date": "2009-02-02T00:00:00.000-0800",
            "price": 80,
            "currency": "USD"
        },
        "thumbnailUrl": "https://s3.amazonaws.com/AKIAJC5RLADLUMVRPFDQ.book-thumb-images/ahmed.jpg",
        "longDescription": "New web applications require engaging user-friendly interfaces   and the cooler, the better. With Flex 3, web developers at any skill level can create high-quality, effective, and interactive Rich Internet Applications (RIAs) quickly and easily. Flex removes the complexity barrier from RIA development by offering sophisticated tools and a straightforward programming language so you can focus on what you want to do instead of how to do it. And now that the major components of Flex are free and open-source, the cost barrier is gone, as well!    Flex 3 in Action is an easy-to-follow, hands-on Flex tutorial. Chock-full of examples, this book goes beyond feature coverage and helps you put Flex to work in real day-to-day tasks. You\'ll quickly master the Flex API and learn to apply the techniques that make your Flex applications stand out from the crowd.    Interesting themes, styles, and skins  It\'s in there.  Working with databases  You got it.  Interactive forms and validation  You bet.  Charting techniques to help you visualize data  Bam!  The expert authors of Flex 3 in Action have one goal   to help you get down to business with Flex 3. Fast.    Many Flex books are overwhelming to new users   focusing on the complexities of the language and the super-specialized subjects in the Flex eco-system; Flex 3 in Action filters out the noise and dives into the core topics you need every day. Using numerous easy-to-understand examples, Flex 3 in Action gives you a strong foundation that you can build on as the complexity of your projects increases.",
        "status": "PUBLISH",
        "authors": ["Tariq Ahmed with Jon Hirschi", "Faisal Abid"],
        "categories": ["Internet"]
    },
    {
        "title": "Flex 4 in Action",
        "isbn": "1935182420",
        "pageCount": 600,
        "published": {
            "$date": "2010-11-15T00:00:00.000-0800",
            "price": 20,
            "currency": "USD"
        },
        "thumbnailUrl": "https://s3.amazonaws.com/AKIAJC5RLADLUMVRPFDQ.book-thumb-images/ahmed2.jpg",
        "longDescription": "Using Flex, you can create high-quality, effective, and interactive Rich Internet Applications (RIAs) quickly and easily. Flex removes the complexity barrier from RIA development by offering sophisticated tools and a straightforward programming language so you can focus on what you want to do instead of how to do it. And the new features added in Flex 4 give you an even wider range of options!    Flex 4 in Action is an easy-to-follow, hands-on Flex tutorial that goes beyond feature coverage and helps you put Flex to work in real day-to-day tasks. You\'ll quickly master the Flex API and learn to apply the techniques that make your Flex applications stand out from the crowd.    The expert authors of Flex 4 in Action have one goal-to help you get down to business with Flex. Fast. Flex 4 in Action filters out the noise and dives into the core topics you need every day. Using numerous easy-to-understand examples, Flex 4 in Action gives you a strong foundation that you can build on as the complexity of your projects increases.    Interesting themes, styles, and skins  It\'s in there.  Working with databases  You got it.  Interactive forms and validation  You bet.  Charting techniques to help you visualize data  Bam!  And you\'ll get full coverage of these great Flex 4 upgrades:  Next generation Spark components-New buttons, form inputs, navigation controls and other visual components replace the Flex 3 \"Halo\" versions. Spark components are easier to customize, which makes skinning and theme design much faster  A new \"network monitor\" allows you to see the data communications between a Flex application and a backend server, which helps when trying to debug applications that are communicating to another system/service  Numerous productivity boosting features that speed up the process of creating applications  A faster compiler to take your human-written source code and convert it into a machine-readable format  Built-in support for unit testing allows you to improve the quality of your software, and reduce the time spent in testing",
        "status": "PUBLISH",
        "authors": ["Tariq Ahmed", "Dan Orlando", "John C. Bland II", "Joel Hooks"],
        "categories": ["Internet"]
    }
]';
    }
}