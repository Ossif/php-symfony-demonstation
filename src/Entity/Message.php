<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $senderId = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $receiverId = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $text = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $datetime = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $fileway = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSenderId(): ?User
    {
        return $this->senderId;
    }

    public function setSenderId(?User $senderId): self
    {
        $this->senderId = $senderId;

        return $this;
    }

    public function getReceiverId(): ?User
    {
        return $this->receiverId;
    }

    public function setReceiverId(?User $receiverId): self
    {
        $this->receiverId = $receiverId;

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

    public function getDatetime(): ?\DateTime
    {
        return $this->datetime;
    }

    public function getDatetimeString(): string
    {
        return $this->datetime->format("dd-mm-yy H:i");
    }

    public function setDatetime(\DateTime $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getFileway(): ?string
    {
        return $this->fileway;
    }

    public function setFileway(?string $fileway): self
    {
        $this->fileway = $fileway;

        return $this;
    }
}
