<?php

namespace App\Repository;

use App\Component\Request\FilterParams;
use App\Entity\Book;
use App\Helper\QueryBuilderHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
        $this->getEntityManager()->flush($book);
    }

    public function search(FilterParams $filterParams)
    {
        $qb = $this->createQueryBuilder($tableAlias = 'b');

        QueryBuilderHelper::addFilterParamsToQueryBuilder($qb, $tableAlias, $filterParams);

        return $qb->getQuery()->getResult();
    }
}