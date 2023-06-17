<?php

declare(strict_types=1);

namespace App\Component\Request;

use App\Exception\InvalidParameterException;

class FilterParams
{
    public const KEYWORD_LIKE = 'like';
    public const KEYWORD_EQUAL = 'eq';
    public const KEYWORD_LESS_THAN_EQUAL = 'lte';
    public const KEYWORD_GREATER_THAN_EQUAL = 'gte';
    public const KEYWORD_LESS_THAN = 'lt';
    public const KEYWORD_GREATER_THAN = 'gt';
    public const KEYWORD_IN = 'in';

    protected const ALLOWED_KEYWORDS = [
        self::KEYWORD_LIKE => true,
        self::KEYWORD_EQUAL => true,
        self::KEYWORD_LESS_THAN_EQUAL => true,
        self::KEYWORD_GREATER_THAN_EQUAL => true,
        self::KEYWORD_LESS_THAN => true,
        self::KEYWORD_GREATER_THAN => true,
        self::KEYWORD_IN => true,
    ];

    private array $filterData;

    public function __construct(array $filters = [])
    {
        $this->filterData = $this->parseFilters($filters);
    }

    public function getData(): array
    {
        return $this->filterData;
    }

    protected function isAllowedKeyword(string $keyword): bool
    {
        return isset(self::ALLOWED_KEYWORDS[$keyword]);
    }

    protected function parseFilters(array $filters): array
    {
        foreach ($filters as &$filter) {
            if (!is_array($filter)) {
                throw new InvalidParameterException('filter.invalid_filter_value');
            }
            foreach ($filter as $keyword => $value) {
                if (is_numeric($keyword)) {
                    unset($filter[$keyword]);
                    $filter[self::KEYWORD_EQUAL] = $value;
                    continue;
                }
                if (!$this->isAllowedKeyword($keyword)) {
                    throw new InvalidParameterException('filter.keyword_not_allowed');
                }
            }
        }
        unset($filter);

        return $filters;
    }
}
