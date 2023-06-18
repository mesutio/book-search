<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'categories')]
#[ORM\Entity(repositoryClass: 'App\Repository\BookCategoryRepository')]
class BookCategory implements BookRelationInterface
{
    #[ORM\Column(name: 'id')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ORM\Column(name: 'category_name', type: 'string', nullable: false)]
    private string $categoryName;

    #[ORM\ManyToMany(targetEntity: "Book", inversedBy: "categories")]
    #[ORM\JoinTable(name: "book_categories")]
    #[ORM\JoinColumn(name: "category_id", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "book_id", referencedColumnName: "id")]
    #[ORM\Cache(usage: 'NONSTRICT_READ_WRITE')]
    private Collection $books;

    public function setCategoryName(string $categoryName): void
    {
        $this->categoryName = $categoryName;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCategoryName(): string
    {
        return $this->categoryName;
    }
}