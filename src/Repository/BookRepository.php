<?php

namespace App\Repository;

use App\Component\Request\FilterParams;
use App\Entity\Book;
use App\Entity\BookCategory;
use App\Helper\QueryBuilderHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

class BookRepository extends ServiceEntityRepository
{
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

        return $qb->getQuery()->getResult();
    }
}