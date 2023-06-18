<?php

namespace App\Tests\unit\Command;

use App\Command\BookApiSyncCommand;
use App\Exception\ClientException;
use App\Service\BookSyncService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BookApiSyncCommandTest extends TestCase
{
    private BookSyncService|MockObject $bookSyncService;
    private LoggerInterface|MockObject $logger;
    private BookApiSyncCommand $command;

    protected function setUp(): void
    {
        $this->bookSyncService = $this->createMock(BookSyncService::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->command = new BookApiSyncCommand($this->bookSyncService, $this->logger);
    }

    public function testGetDescription()
    {
        $this->assertEquals('Fetching latest data from book API.', $this->command->getDescription());
    }

    public function testExecuteSuccess()
    {
        $input = $this->getMockForAbstractClass(InputInterface::class);
        $output = $this->getMockForAbstractClass(OutputInterface::class);

        $this->bookSyncService->expects(static::once())
            ->method('sync');

        $this->logger->expects(static::never())
            ->method('error');

        $reflection = new \ReflectionClass(get_class($this->command));
        $method = $reflection->getMethod('execute');
        $method->setAccessible(true);

        $method->invoke($this->command, $input, $output);
    }

    public function testExecuteFailed()
    {
        $input = $this->getMockForAbstractClass(InputInterface::class);
        $output = $this->getMockForAbstractClass(OutputInterface::class);

        $this->bookSyncService->expects(static::once())
            ->method('sync')
            ->willThrowException($this->createMock(ClientException::class));

        $this->logger->expects(static::once())
            ->method('error');

        $reflection = new \ReflectionClass(get_class($this->command));
        $method = $reflection->getMethod('execute');
        $method->setAccessible(true);

        $method->invoke($this->command, $input, $output);
    }

    public function testExecuteRuntimeExceptionFailed()
    {
        $input = $this->getMockForAbstractClass(InputInterface::class);
        $output = $this->getMockForAbstractClass(OutputInterface::class);

        $this->bookSyncService->expects(static::once())
            ->method('sync')
            ->willThrowException($this->createMock(\RuntimeException::class));

        $this->logger->expects(static::once())
            ->method('error');

        $reflection = new \ReflectionClass(get_class($this->command));
        $method = $reflection->getMethod('execute');
        $method->setAccessible(true);

        $method->invoke($this->command, $input, $output);
    }
}