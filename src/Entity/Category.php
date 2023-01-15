<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $categoryName = null;

    #[ORM\OneToMany(mappedBy: 'categoryType', targetEntity: ItemToBorrow::class)]
    private Collection $item;

    public function __construct()
    {
        $this->item = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoryName(): ?string
    {
        return $this->categoryName;
    }

    public function setCategoryName(string $categoryName): self
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    /**
     * @return Collection<int, ItemToBorrow>
     */
    public function getItem(): Collection
    {
        return $this->item;
    }

    public function addItem(ItemToBorrow $item): self
    {
        if (!$this->item->contains($item)) {
            $this->item[] = $item;
            $item->setCategoryType($this);
        }

        return $this;
    }

    public function removeItem(ItemToBorrow $item): self
    {
        if ($this->item->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getCategoryType() === $this) {
                $item->setCategoryType(null);
            }
        }

        return $this;
    }
}
