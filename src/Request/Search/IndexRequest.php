<?php

namespace App\Request\Search;

use App\Component\Request\FilterParams;

class IndexRequest
{
    protected static array $allowedFilters = [
        'price' => 'b.price',
        'date'  => 'b.date',
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