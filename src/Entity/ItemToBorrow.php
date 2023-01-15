<?php

namespace App\Entity;

use App\Repository\ItemToBorrowRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ItemToBorrowRepository::class)]
#[Vich\Uploadable]
class ItemToBorrow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Ce champ ne peut pas être vide')]
    private ?string $name = null;

    // #[ORM\Column(length: 255, nullable: true)]
    // private ?string $category = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(message: 'Ce champ ne peut pas être vide')]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    #[Vich\UploadableField(mapping: 'images', fileNameProperty: 'picture')]
    #[Assert\File(
        maxSize: '1M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
        mimeTypesMessage: 'Ce fichier doit être une image',
        uploadFormSizeErrorMessage: 'Votre photo ne peut pas dépasser 1Mo'
    )]
    private ?File $pictureFile = null;

    #[ORM\ManyToOne(inversedBy: 'itemToBorrows')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userWhoOffer = null;

    #[ORM\OneToMany(mappedBy: 'borrowedItem', targetEntity: Borrow::class, orphanRemoval: true)]
    private Collection $borrows;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'item')]
    #[ORM\JoinColumn(nullable: false)]
    private Category $categoryType;

    public function __construct()
    {
        $this->borrows = new ArrayCollection();
    }

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

    // public function getCategory(): ?string
    // {
    //     return $this->category;
    // }

    // public function setCategory(?string $category): self
    // {
    //     $this->category = $category;

    //     return $this;
    // }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function setPictureFile(?File $pictureFile = null): void
    {
        $this->pictureFile = $pictureFile;

        if (null !== $pictureFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getPictureFile(): ?File
    {
        return $this->pictureFile;
    }

    public function getUserWhoOffer(): ?User
    {
        return $this->userWhoOffer;
    }

    public function setUserWhoOffer(?User $userWhoOffer): self
    {
        $this->userWhoOffer = $userWhoOffer;

        return $this;
    }

    /**
     * @return Collection<int, Borrow>
     */
    public function getBorrows(): Collection
    {
        return $this->borrows;
    }

    public function addBorrow(Borrow $borrow): self
    {
        if (!$this->borrows->contains($borrow)) {
            $this->borrows[] = $borrow;
            $borrow->setBorrowedItem($this);
        }

        return $this;
    }

    public function removeBorrow(Borrow $borrow): self
    {
        if ($this->borrows->removeElement($borrow)) {
            // set the owning side to null (unless already changed)
            if ($borrow->getBorrowedItem() === $this) {
                $borrow->setBorrowedItem(null);
            }
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategoryType(): ?Category
    {
        return $this->categoryType;
    }

    public function setCategoryType(?Category $categoryType): self
    {
        $this->categoryType = $categoryType;

        return $this;
    }
}
