<?php

namespace App\Entity;

use App\Repository\BlackListRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlackListRepository::class)]
class BlackList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userId = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Admin $adminId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reason = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $datetimeBegin = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $datetimeEnd = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getAdminId(): ?Admin
    {
        return $this->adminId;
    }

    public function setAdminId(?Admin $adminId): self
    {
        $this->adminId = $adminId;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getDatetimeBegin(): ?\DateTime
    {
        return $this->datetimeBegin;
    }

    public function setDatetimeBegin(\DateTime $datetimeBegin): self
    {
        $this->datetimeBegin = $datetimeBegin;

        return $this;
    }

    public function getDatetimeEnd(): ?\DateTime
    {
        return $this->datetimeEnd;
    }

    public function getDatetimeEndString(): string
    {
        return $this->datetimeEnd->format('d-m-Y H:i');
    }

    public function setDatetimeEnd(\DateTime $datetimeEnd): self
    {
        $this->datetimeEnd = $datetimeEnd;

        return $this;
    }
}
