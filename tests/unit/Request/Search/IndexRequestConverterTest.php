<?php

namespace App\Tests\unit\Request\Search;

use App\Component\Request\FilterParams;
use App\Request\Search\IndexRequest;
use App\Request\Search\IndexRequestConverter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class IndexRequestConverterTest extends TestCase
{
    private ParamConverter|MockObject $configuration;
    private IndexRequestConverter $converter;

    public function setUp(): void
    {
        $this->configuration = $this->createMock(ParamConverter::class);
        $this->converter = new IndexRequestConverter();
    }

    public function testSupports()
    {
        $this->configuration->expects(static::once())
            ->method('getClass')->willReturn(IndexRequest::class);

        $this->assertTrue($this->converter->supports($this->configuration));
    }

    public function testNotSupports()
    {
        $this->configuration->expects(static::once())
            ->method('getClass')->willReturn('Invalid');

        $this->assertFalse($this->converter->supports($this->configuration));
    }

    public function testApplySuccess(): void
    {
        $request = $this->createMock(Request::class);
        $requestParams = ['price' => 30, 'category' => 'Mobile', 'date' => 2011];
        $request->method('get')->willReturnCallback(fn($var1) => $requestParams[$var1] ?? null);

        $this->configuration->expects(static::once())
            ->method('getName')
            ->willReturn(IndexRequest::class);

        $request->attributes = $paramsBag = new ParameterBag();
        $this->converter->apply($request, $this->configuration);
        /** @var IndexRequest $searchIndexRequest */
        $searchIndexRequest = $paramsBag->get(IndexRequest::class);

        $this->assertInstanceOf(IndexRequest::class, $searchIndexRequest);

        $this->assertEquals([
            'price.amount' => ['eq' => 30],
            'bc.categoryName' => ['eq' => 'Mobile'],
            'date' => ['like' => 2011],
        ], $searchIndexRequest->getFilterParams()->getData());
    }
}