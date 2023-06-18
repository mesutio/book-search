<?php

namespace App\Tests\unit\Component\Mapper;

use App\Component\Mapper\BookMapper;
use App\Entity\Book;
use PHPUnit\Framework\TestCase;

class BookMapperTest extends TestCase
{
    /**
     * @dataProvider bookApiResponseDataProvider
     */
    public function testSuccessMap(array $bookApiResponse)
    {
        $book = new Book();
        $mappedBook = BookMapper::map($book, $bookApiResponse);

        $this->assertEquals($mappedBook->getTitle(), $bookApiResponse['title']);
        $this->assertEquals($mappedBook->getIsbn(), $bookApiResponse['isbn'] ?? null);
        $this->assertEquals($mappedBook->getPageCount(), $bookApiResponse['pageCount']);
        $this->assertEquals($mappedBook->getThumbnailUrl(), $bookApiResponse['thumbnailUrl'] ?? null);
        $this->assertEquals($mappedBook->getStatus(), $bookApiResponse['status']);
        $this->assertEquals($mappedBook->getLongDescription(), $bookApiResponse['longDescription'] ?? null);
        $this->assertEquals($mappedBook->getShortDescription(), $bookApiResponse['shortDescription'] ?? null);
    }

    public static function bookApiResponseDataProvider(): array
    {
        return [
            [
                json_decode('{
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
	}', true),
            ],
            [
                json_decode('{
        "title": "Collective Intelligence in Action",
        "isbn": "1933988312",
        "pageCount": 425,
        "published": {
            "$date": "2008-10-01T00:00:00.000-0700",
            "price": 40,
            "currency": "USD"
        },
        "thumbnailUrl": "https://s3.amazonaws.com/AKIAJC5RLADLUMVRPFDQ.book-thumb-images/alag.jpg",
        "longDescription": "There\'s a great deal of wisdom in a crowd, but how do you listen to a thousand people talking at once  Identifying the wants, needs, and knowledge of internet users can be like listening to a mob.    In the Web 2.0 era, leveraging the collective power of user contributions, interactions, and feedback is the key to market dominance. A new category of powerful programming techniques lets you discover the patterns, inter-relationships, and individual profiles   the collective intelligence   locked in the data people leave behind as they surf websites, post blogs, and interact with other users.    Collective Intelligence in Action is a hands-on guidebook for implementing collective-intelligence concepts using Java. It is the first Java-based book to emphasize the underlying algorithms and technical implementation of vital data gathering and mining techniques like analyzing trends, discovering relationships, and making predictions. It provides a pragmatic approach to personalization by combining content-based analysis with collaborative approaches.    This book is for Java developers implementing collective intelligence in real, high-use applications. Following a running example in which you harvest and use information from blogs, you learn to develop software that you can embed in your own applications. The code examples are immediately reusable and give the Java developer a working collective intelligence toolkit.    Along the way, you work with, a number of APIs and open-source toolkits including text analysis and search using Lucene, web-crawling using Nutch, and applying machine learning algorithms using WEKA and the Java Data Mining (JDM) standard.",
        "status": "PUBLISH",
        "authors": ["Satnam Alag"],
        "categories": ["Internet"]
    }', true),
            ],
            [
                json_decode('{
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
    }', true),
            ],
            [
                json_decode('{
        "title": "Distributed Application Development with PowerBuilder 6.0",
        "isbn": "1884777686",
        "pageCount": 504,
        "published": {
            "$date": "1998-06-01T00:00:00.000-0700",
            "price": 10,
            "currency": "USD"
        },
        "longDescription": "Distributed Application Development with PowerBuilder 6.0 is a vital source for the PowerBuilder programmer; it provides the sort of detailed coverage of Distributed PowerBuilder that you can find nowwhere else.    The book opens with a discussion of distributed computing in general, as well as its design principles and technologies. Then Distributed PowerBuilder is examined in detail. By building a simple application step by step, the author discusses all of the concepts and components needed for building a PowerBuilder application and shows how to make the application available over a network.    Finally, the author explores how PowerBuilder can be used in distributed solutions both with and without using DPB.    Distributed Application Development with PowerBuilder 6.0 is for any PowerBuilder developer looking for information on distributed computing options with the PowerBuilder environment. IS managers, system architects, and developers using many different technologies can learn how PowerBuilder can be used as all or part of the solution for building distributed applications.    The main topic of this book is Distributed PowerBuilder (DPB). It covers the basics of building a DPB application and walks through each new feature with examples including the Shared object, DataWindow synchronization, Server Push and Web.PB. It also explains distributed computing technologies and design principles so that your application can be built to handle the stresses of a distributed environment.  ",
        "status": "PUBLISH",
        "authors": ["Michael J. Barlotta"],
        "categories": ["PowerBuilder"]
    }', true),
            ],
            [
                json_decode('{
        "title": "Hibernate in Action (Chinese Edition)",
        "pageCount": 400,
        "published": {
            "$date": "1999-06-01T00:00:00.000-0700",
            "price": 90,
            "currency": "USD"
        },
        "thumbnailUrl": "https://s3.amazonaws.com/AKIAJC5RLADLUMVRPFDQ.book-thumb-images/bauer-cn.jpg",
        "status": "PUBLISH",
        "authors": ["Christian Bauer", "Gavin King"],
        "categories": ["Java"]
    }', true)
            ]
        ];
    }
}