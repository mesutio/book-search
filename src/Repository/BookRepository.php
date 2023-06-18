<?php

namespace App\Repository;

use App\Component\Request\FilterParams;
use App\Entity\Book;
use App\Helper\QueryBuilderHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BookRepository extends ServiceEntityRepository
{
    private const SEARCH_CACHE_EXPIRE = 3600;
    private const SEARCH_CACHE_ID = 'book_search';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function save(Book $book): void
    {
        $this->getEntityManager()->persist($book);
        $this->getEntityManager()->flush();
    }

    public function search(FilterParams $filterParams): array
    {
        $otherAliases = [
            $categoryRelationAlias = 'bc',
        ];

        $qb = $this->createQueryBuilder($tableAlias = 'b')
                   ->innerJoin('b.categories', $categoryRelationAlias);

        QueryBuilderHelper::addFilterParamsToQueryBuilder($qb, $tableAlias, $filterParams, $otherAliases);

        return $qb->getQuery()->useQueryCache(true)->enableResultCache(self::SEARCH_CACHE_EXPIRE, $this->makeSearchCacheKey($filterParams))->getResult();
    }

    private function makeSearchCacheKey(FilterParams $filterParams): string
    {
        return sprintf('%s_%s', self::SEARCH_CACHE_ID, md5(json_encode($filterParams->getData())));
    }
}
