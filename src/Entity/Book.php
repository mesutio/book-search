<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Money\Money;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'books')]
#[ORM\Entity(repositoryClass: 'App\Repository\BookRepository')]
#[ORM\HasLifecycleCallbacks]
#[ORM\Cache(usage: 'NONSTRICT_READ_WRITE')]
class Book
{
    #[ORM\Column(name: 'id')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ORM\Column(name: 'title', type: 'string', nullable: false)]
    private string $title;

    #[ORM\Column(name: 'isbn', type: 'string', nullable: true)]
    private ?string $isbn;

    #[ORM\Column(name: 'page_count', type: 'integer', nullable: false)]
    private int $pageCount;

    #[ORM\Column(name: 'date', type: 'date', nullable: false)]
    private \DateTimeInterface $date;

    #[ORM\Embedded(class: '\Money\Money')]
    private Money $price;

    #[ORM\Column(name: 'thumbnail_url', type: 'string', nullable: true)]
    private ?string $thumbnailUrl;

    #[ORM\Column(name: 'short_description', type: 'text', nullable: true)]
    private ?string $shortDescription;

    #[ORM\Column(name: 'long_description', type: 'text', nullable: true)]
    private ?string $longDescription;

    #[ORM\Column(name: 'status', type: 'string', nullable: false)]
    private string $status;

    #[ORM\ManyToMany(targetEntity: "BookCategory", inversedBy: "books")]
    #[ORM\JoinTable(name: "book_categories")]
    #[ORM\JoinColumn(name: "book_id", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "category_id", referencedColumnName: "id")]
    #[ORM\Cache(usage: 'NONSTRICT_READ_WRITE')]
    private Collection $categories;

    #[ORM\ManyToMany(targetEntity: "BookAuthor", inversedBy: "books")]
    #[ORM\JoinTable(name: "book_authors")]
    #[ORM\JoinColumn(name: "book_id", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "author_id", referencedColumnName: "id")]
    private Collection $authors;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->authors = new ArrayCollection();
    }

    public function addAuthor(BookAuthor $bookAuthor)
    {
        $this->authors->add($bookAuthor);
    }

    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function removeAuthors(): void
    {
        foreach ($this->authors as $author) {
            $this->authors->removeElement($author);
        }
    }

    public function addCategory(BookCategory $category)
    {
        $this->categories->add($category);
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function removeCategories(): void
    {
        foreach ($this->categories as $category) {
            $this->categories->removeElement($category);
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(?string $isbn): void
    {
        $this->isbn = $isbn;
    }

    public function getPageCount(): int
    {
        return $this->pageCount;
    }

    public function setPageCount(int $pageCount): void
    {
        $this->pageCount = $pageCount;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function setPrice(Money $price): void
    {
        $this->price = $price;
    }

    public function getThumbnailUrl(): ?string
    {
        return $this->thumbnailUrl;
    }

    public function setThumbnailUrl(?string $thumbnailUrl): void
    {
        $this->thumbnailUrl = $thumbnailUrl;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): void
    {
        $this->shortDescription = $shortDescription;
    }

    public function getLongDescription(): ?string
    {
        return $this->longDescription;
    }

    public function setLongDescription(?string $longDescription): void
    {
        $this->longDescription = $longDescription;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new \DateTime();
    }
}