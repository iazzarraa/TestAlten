<?php
// src/Entity/Product.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Product
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    #[Groups(['cart:read','wishlist:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['cart:read','wishlist:read'])]
    private string $code;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['cart:read','wishlist:read'])]
    private string $name;

    #[ORM\Column(type: 'text')]
    #[Groups(['cart:read','wishlist:read'])]
    private string $description;

    #[ORM\Column(type: 'string')]
    #[Groups(['cart:read','wishlist:read'])]
    private string $image;

    #[ORM\Column(type: 'string')]
    #[Groups(['cart:read','wishlist:read'])]
    private string $category;

    #[ORM\Column(type: 'float')]
    #[Groups(['cart:read','wishlist:read'])]
    private float $price;

    #[ORM\Column(type: 'integer')]
    #[Groups(['cart:read','wishlist:read'])]
    private int $quantity;

    #[ORM\Column(type: 'string')]
    #[Groups(['cart:read','wishlist:read'])]
    private string $internalReference;

    #[ORM\Column(type: 'integer')]
    #[Groups(['cart:read','wishlist:read'])]
    private int $shellId;

    #[ORM\Column(type: 'string')]
    #[Groups(['cart:read','wishlist:read'])]
    private string $inventoryStatus;

    #[ORM\Column(type: 'float')]
    #[Groups(['cart:read','wishlist:read'])]
    private float $rating;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $createdAt;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $updatedAt;

    // Getters et setters
    public function getId(): ?int 
    { 
        return $this->id; 
    }

    public function getCode(): string 
    { 
        return $this->code; 
    }

    public function setCode(string $code): self 
    { 
        $this->code = $code; return $this; 
    }

    public function getName(): string 
    { 
        return $this->name; 
    }

    public function setName(string $name): self 
    { 
        $this->name = $name; return $this; 
    }

    public function getDescription(): string 
    { 
        return $this->description; 
    }

    public function setDescription(string $description): self 
    {
        $this->description = $description; 
        return $this; 
    }

    public function getImage(): string 
    {
        return $this->image; 
    }

    public function setImage(string $image): self 
    { 
        $this->image = $image; return $this; 
    }

    public function getCategory(): string 
    {
        return $this->category; 
    }

    public function setCategory(string $category): self 
    { 
        $this->category = $category; 
        return $this; 
    }

    public function getPrice(): float 
    { 
        return $this->price; 
    }

    public function setPrice(float $price): self 
    { 
        $this->price = $price; 
        return $this; 
    }

    public function getQuantity(): int 
    { 
        return $this->quantity; 
    }

    public function setQuantity(int $quantity): self 
    { 
        $this->quantity = $quantity; 
        return $this; 
    }

    public function getInternalReference(): string 
    { 
        return $this->internalReference; 
    }

    public function setInternalReference(string $internalReference): self 
    { 
        $this->internalReference = $internalReference; 
        return $this; 
    }

    public function getShellId(): int 
    { 
        return $this->shellId; 
    }

    public function setShellId(int $shellId): self 
    { 
        $this->shellId = $shellId; 
        return $this; 
    }

    public function getInventoryStatus(): string 
    { 
        return $this->inventoryStatus; 
    }

    public function setInventoryStatus(string $inventoryStatus): self 
    { 
        $this->inventoryStatus = $inventoryStatus; 
        return $this; 
    }

    public function getRating(): float 
    { 
        return $this->rating; 
    }

    public function setRating(float $rating): self 
    { 
        $this->rating = $rating; 
        return $this; 
    }

    public function getCreatedAt(): \DateTime 
    { 
        return $this->createdAt; 
    }

    public function setCreatedAt(\DateTime $createdAt): self 
    { 
        $this->createdAt = $createdAt; 
        return $this; 
    }

    public function getUpdatedAt(): \DateTime 
    { 
        return $this->updatedAt; 
    }

    public function setUpdatedAt(\DateTime $updatedAt): self 
    { 
        $this->updatedAt = $updatedAt; 
        return $this; 
    }
}
