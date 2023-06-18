<?php

namespace App\Tests\unit\Request\Search;

use App\Component\Request\FilterParams;
use App\Request\Search\IndexRequest;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class IndexRequestTest extends TestCase
{
    private FilterParams|MockObject $filterParams;
    private IndexRequest $request;

    public function setUp(): void
    {
        $this->filterParams = $this->createMock(FilterParams::class);

        $this->request = new IndexRequest($this->filterParams);
    }

    public function testGetFilterParams()
    {
        $this->assertInstanceOf(FilterParams::class, $this->request->getFilterParams());
    }

    public function testGetAllowedFilters()
    {
        $this->assertEquals([
            FilterParams::KEYWORD_EQUAL => [
                'price'     => 'price.amount',
                'category'  => 'bc.categoryName'
            ],
            FilterParams::KEYWORD_LIKE => ['date' => 'date'],
        ], IndexRequest::getAllowedFilters());
    }
}