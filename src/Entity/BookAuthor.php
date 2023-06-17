<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'authors')]
#[ORM\Entity(repositoryClass: 'App\Repository\BookAuthorRepository')]
class BookAuthor implements BookRelationInterface
{
    #[ORM\Column(name: 'id')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ORM\Column(name: 'author_name', type: 'string', nullable: false)]
    private string $authorName;

    public function setAuthorName(string $authorName): void
    {
        $this->authorName = $authorName;
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    public function getId(): int
    {
        return $this->id;
    }

}