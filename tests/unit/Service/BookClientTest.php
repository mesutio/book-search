<?php

namespace App\Tests\unit\Service;

use App\Exception\ClientException;
use App\Service\BookClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * @group client
 */
class BookClientTest extends TestCase
{
    private LoggerInterface|MockObject $logger;
    private ClientInterface|MockObject $client;
    private BookClient $bookClient;

    public function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->client = $this->createMock(ClientInterface::class);

        $this->bookClient = new BookClient($this->client, $this->logger, 'base-uri');
    }

    public function testSuccessRequest(): void
    {
        $this->client->expects(static::once())
            ->method('request')
            ->willReturn($this->createMock(ResponseInterface::class));

        $this->logger->expects(static::never())
            ->method('alert');

        $this->bookClient->request('get', '/path');
    }

    public function testFailedRequest(): void
    {
        $this->expectException(ClientException::class);
        $this->client->expects(static::once())
            ->method('request')
            ->willThrowException($this->createMock(GuzzleException::class));

        $this->logger->expects(static::once())
            ->method('alert');

        $this->bookClient->request('get', '/path');
    }

    public function testSuccessGet(): void
    {
        $this->client->expects(static::once())
            ->method('request')
            ->willReturn($this->createMock(ResponseInterface::class));

        $this->logger->expects(static::never())
            ->method('alert');

        $this->bookClient->get('/path');
    }

    public function testFailedGet(): void
    {
        $this->client->expects(static::once())
            ->method('request')
            ->willReturn($this->createMock(ResponseInterface::class));

        $this->logger->expects(static::never())
            ->method('alert');

        $this->bookClient->get('/path');
    }
}