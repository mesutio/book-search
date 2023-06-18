<?php

namespace App\Helper;

use App\Component\Request\FilterParams;
use Doctrine\ORM\QueryBuilder;

class QueryBuilderHelper
{
    protected const KEYWORDS_MAPPING = [
        FilterParams::KEYWORD_LIKE => 'LIKE',
        FilterParams::KEYWORD_EQUAL => '=',
        FilterParams::KEYWORD_LESS_THAN_EQUAL => '<=',
        FilterParams::KEYWORD_GREATER_THAN_EQUAL => '>=',
        FilterParams::KEYWORD_LESS_THAN => '<',
        FilterParams::KEYWORD_GREATER_THAN => '>',
        FilterParams::KEYWORD_IN => 'in',
    ];

    public static function addFilterParamsToQueryBuilder(
        QueryBuilder $queryBuilder,
        string $tableAlias,
        FilterParams $filterParams,
        array $otherAliases = [],
        bool $orCondition = false,
    ): QueryBuilder {
        foreach ($filterParams->getData() as $field => $filter) {
            $i = 0;
            foreach ($filter as $operator => $value) {
                ++$i;
                $relation = false;
                if (FilterParams::KEYWORD_LIKE === $operator) {
                    $value = trim(StringHelper::cleanupSpecialChars((string) ($value ?? '')));
                    if (empty($value) || !is_string($value)) {
                        continue;
                    }

                    $value = StringHelper::truncate($value, 50);
                    $value = '%'.$value.'%';
                }
                $operator = static::KEYWORDS_MAPPING[$operator];
                $whereFmt = '%s.%s %s :%s';
                if (FilterParams::KEYWORD_IN === $operator) {
                    $whereFmt = '%s.%s %s (:%s)';
                }

                $fieldSeperatedByDots = explode('.', $field);
                if (in_array($fieldSeperatedByDots[0], $otherAliases)) {
                    $whereFmt = '%s %s (:%s)';
                    $relation = true;
                }

                $uniqueParamName = sprintf('%s_%s_%d', $tableAlias, $field, $i);
                if (str_contains($uniqueParamName, '.')) {
                    $uniqueParamName = str_replace('.', '_', $uniqueParamName);
                }

                $whereClosure = $relation ? sprintf($whereFmt, $field, $operator, $uniqueParamName) : sprintf($whereFmt, $tableAlias, $field, $operator, $uniqueParamName);
                if ($orCondition) {
                    $queryBuilder->orWhere($whereClosure)
                        ->setParameter($uniqueParamName, $value);
                } else {
                    $queryBuilder->andWhere($whereClosure)
                        ->setParameter($uniqueParamName, $value);
                }
            }
        }

        return $queryBuilder;
    }
}
