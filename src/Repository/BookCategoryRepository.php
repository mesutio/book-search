<?php

namespace App\Repository;

use App\Entity\BookCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BookCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookCategory::class);
    }

    public function save(BookCategory $category): void
    {
        $this->getEntityManager()->persist($category);
        $this->getEntityManager()->flush();
    }
}