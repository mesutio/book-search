<?php

namespace App\Request;

use Symfony\Component\HttpFoundation\Request;

abstract class AbstractFilterConverter
{
    protected function parseFilters(Request $request): array
    {
        $parsedFilterList = [];
        foreach ($this->getAllowedFilters() as $filterName => $fieldName) {
            $filter = $request->get($filterName);
            if (empty($filter)) {
                continue;
            }
            $parsedFilterList[$fieldName][] = $filter;
        }

        return $parsedFilterList;
    }

    abstract protected function getAllowedFilters(): array;
}