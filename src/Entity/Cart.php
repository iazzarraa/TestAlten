<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['cart:read'])]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'cart')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['cart:read'])]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: CartItem::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups(['cart:read'])]
    private Collection $cartItems;

    public function __construct()
    {
        $this->cartItems = new ArrayCollection();
    }

    public function getId(): ?int 
    { 
        return $this->id; 
    }

    public function getUser(): ?User 
    { 
        return $this->user; 
    }

    public function setUser(?User $user): static 
    { 
        $this->user = $user; 
        return $this; 
    }

    public function getCartItems(): Collection 
    { 
        return $this->cartItems; 
    }

    public function addCartItem(CartItem $item): static
    {
        if (!$this->cartItems->contains($item)) {
            $this->cartItems[] = $item;
            $item->setCart($this);
        }
        return $this;
    }

    public function removeCartItem(CartItem $item): static
    {
        if ($this->cartItems->removeElement($item)) {
            if ($item->getCart() === $this) {
                $item->setCart(null);
            }
        }
        return $this;
    }
}
