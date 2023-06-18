<?php

declare(strict_types=1);

namespace App\Tests\unit\Component\Request;

use App\Component\Request\FilterParams;
use App\Exception\InvalidParameterException;
use PHPUnit\Framework\TestCase;

class FilterParamsTest extends TestCase
{
    public function testCreateFilterParams(): void
    {
        $filterParams = [
            'category' => [
                'like' => 'Mobile',
            ],
            'date'       => [
                'like' => '2011',
            ],
        ];

        $filterParamsObject = new FilterParams($filterParams);
        $this->assertEquals($filterParams, $filterParamsObject->getData());
    }

    /**
     * @dataProvider numericKeywordDataProvider
     */
    public function testCreateFilterParamsWithNumericKeyword($filterParams, $expectedFilterParams): void
    {
        $filterParamsObject = new FilterParams($filterParams);
        $this->assertEquals($expectedFilterParams, $filterParamsObject->getData());
    }

    public function testCreateFilterParamsWithNotAllowedKeywords(): void
    {
        $this->expectException(InvalidParameterException::class);
        $filterParams = [
            'category' => [
                'wrong' => 'Internet',
            ],
            'date'       => [
                'wrong' => '2011-04-01',
            ],
        ];

        new FilterParams($filterParams);
    }

    public function testCreateFilterParamsWithWrongFormatKeyword(): void
    {
        $this->expectException(InvalidParameterException::class);
        $filterParams = [
            'category'    => 'Internet',
            'date'        => '2011-04-01',
        ];

        new FilterParams($filterParams);
    }

    public static function numericKeywordDataProvider(): array
    {
        return [
            [
                [
                    'category' => [
                        'Mobile',
                    ],
                    'date'       => [
                        '2011-04-01',
                    ],
                ],
                [
                    'category' => [
                        'eq' => 'Mobile',
                    ],
                    'date'       => [
                        'eq' => '2011-04-01',
                    ],
                ],
            ],
            [
                [
                    'price' => [
                        12345678 => '123',
                    ],
                    'category'       => [
                        12482 => 'Internet',
                    ],
                ],
                [
                    'price' => [
                        'eq' => '123',
                    ],
                    'category'       => [
                        'eq' => 'Internet',
                    ],
                ],
            ],
        ];
    }
}
