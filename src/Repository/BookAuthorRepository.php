<?php

namespace App\Repository;

use App\Entity\BookAuthor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BookAuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookAuthor::class);
    }

    public function save(BookAuthor $author): void
    {
        $this->getEntityManager()->persist($author);
        $this->getEntityManager()->flush();
    }
}