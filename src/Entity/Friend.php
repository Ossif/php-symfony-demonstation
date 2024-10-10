<?php

namespace App\Entity;

use App\Repository\FriendRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FriendRepository::class)]
class Friend
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $outcoming = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $incoming = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOutcoming(): ?User
    {
        return $this->outcoming;
    }

    public function setOutcoming(?User $outcoming): self
    {
        $this->outcoming = $outcoming;

        return $this;
    }

    public function getIncoming(): ?User
    {
        return $this->incoming;
    }

    public function setIncoming(?User $incoming): self
    {
        $this->incoming = $incoming;

        return $this;
    }
}
