<?php

namespace App\Entity;

use Money\Money;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'book')]
#[ORM\Entity(repositoryClass: 'App\Repository\BookRepository')]
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
}