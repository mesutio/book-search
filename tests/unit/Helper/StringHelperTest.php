<?php

namespace App\Tests\unit\Helper;

use App\Helper\StringHelper;
use PHPUnit\Framework\TestCase;

class StringHelperTest extends TestCase
{
    /**
     * @dataProvider truncateDataProvider
     */
    public function testTruncate(string $text, string $expectedText): void
    {
        $this->assertEquals($expectedText, StringHelper::truncate($text, 20));
    }

    public static function truncateDataProvider(): array
    {
        return [
            'less than 20' => ['hello world', 'hello world'],
            'more than 20' => ['hello world hello world hello world', 'hello world hello...'],
        ];
    }
}