<?php

namespace App\Entity;

use App\Repository\DivisionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DivisionRepository::class)]
class Division
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $articleCount = null;

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

    public function getArticleCount(): ?int
    {
        return $this->articleCount;
    }

    public function setArticleCount(int $articleCount): self
    {
        $this->articleCount = $articleCount;

        return $this;
    }
}
