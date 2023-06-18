<?php

namespace App\Request;

use Symfony\Component\HttpFoundation\Request;

abstract class AbstractFilterConverter
{
    protected function parseFilters(Request $request): array
    {
        $parsedFilterList = [];
        foreach ($this->getAllowedFilters() as $filterName => $filters) {
            foreach ($filters as $fieldName => $queryName) {
                if ($val = $request->get($fieldName)) {
                    $parsedFilterList[$queryName] = [$filterName => $val];
                }
            }
        }

        return $parsedFilterList;
    }

    abstract protected function getAllowedFilters(): array;
}
