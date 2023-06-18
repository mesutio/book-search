<?php

namespace App\Tests\unit\Request\Search;

use App\Component\Request\FilterParams;
use App\Repository\BookRepository;
use App\Request\Search\IndexRequestHandler;
use App\Request\Search\IndexRequest;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class IndexRequestHandlerTest extends TestCase
{
    private BookRepository|MockObject $bookRepository;
    private IndexRequestHandler $handler;

    public function setUp(): void
    {
        $this->bookRepository = $this->createMock(BookRepository::class);

        $this->handler = new IndexRequestHandler($this->bookRepository);
    }

    public function testSuccessInvoke()
    {
        $requestMock = $this->createMock(IndexRequest::class);
        $requestMock->expects(static::once())
                    ->method('getFilterParams')
                    ->willReturn($filterMock = $this->createMock(FilterParams::class));

        $this->bookRepository->expects(static::once())
            ->method('search')
            ->with($filterMock)
            ->willReturn([]);

        $response = $this->handler->__invoke($requestMock);

        $this->assertEquals([
            'total' => 0,
            'data' => [],
        ], $response);
    }
}