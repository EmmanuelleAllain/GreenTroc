<?php

namespace App\Entity;

use App\Repository\BorrowRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BorrowRepository::class)]
class Borrow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'borrows')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ItemToBorrow $borrowedItem = null;

    #[ORM\ManyToOne(inversedBy: 'borrows')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userWhoBorrow = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getBorrowedItem(): ?ItemToBorrow
    {
        return $this->borrowedItem;
    }

    public function setBorrowedItem(?ItemToBorrow $borrowedItem): self
    {
        $this->borrowedItem = $borrowedItem;

        return $this;
    }

    public function getUserWhoBorrow(): ?User
    {
        return $this->userWhoBorrow;
    }

    public function setUserWhoBorrow(?User $userWhoBorrow): self
    {
        $this->userWhoBorrow = $userWhoBorrow;

        return $this;
    }
}
