<?php

namespace App\Request\Search;

use App\Component\Request\FilterParams;

class IndexRequest
{
    protected static array $allowedFilters = [
        FilterParams::KEYWORD_EQUAL => [
            'price' => 'price.amount',
            'category' => 'bc.categoryName',
        ],
        FilterParams::KEYWORD_LIKE => ['date' => 'date'],
    ];

    public function __construct(private FilterParams $filterParams)
    {
    }

    public function getFilterParams(): FilterParams
    {
        return $this->filterParams;
    }

    public static function getAllowedFilters(): array
    {
        return self::$allowedFilters;
    }
}
