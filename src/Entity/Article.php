<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $dateCreation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $dateEdit = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Author $authorId = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Division $divisionId = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $text = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $fileway = null;

    #[ORM\Column]
    private ?int $rating = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDateCreation(): ?\DateTime
    {
        return $this->dateCreation;
    }

    public function getDateCreationString(): string
    {
        $dateCreation = $this->dateCreation->format('d-m-Y H:i:s');

        return $dateCreation;
    }

    public function setDateCreation(\DateTime $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateEdit(): ?\DateTimeInterface
    {
        return $this->dateEdit;
    }

    public function getDateEditString(): string
    {
        $dateEdit = $this->dateEdit->format('d-m-Y H:i:s');

        return $dateEdit;
    }


    public function setDateEdit(\DateTimeInterface $dateEdit): self
    {
        $this->dateEdit = $dateEdit;

        return $this;
    }

    public function getAuthorId(): ?Author
    {
        return $this->authorId;
    }

    public function setAuthorId(?Author $authorId): self
    {
        $this->authorId = $authorId;

        return $this;
    }

    public function getDivisionId(): ?Division
    {
        return $this->divisionId;
    }

    public function setDivisionId(?Division $divisionId): self
    {
        $this->divisionId = $divisionId;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getFileway(): ?string
    {
        return $this->fileway;
    }

    public function setFileway(string $fileway): self
    {
        $this->fileway = $fileway;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }
}
