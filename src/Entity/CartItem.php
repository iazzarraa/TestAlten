<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class CartItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'cartItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cart $cart = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['cart:read'])]
    private ?Product $product = null;

    #[ORM\Column]
    private int $quantity = 1;

    public function getId(): ?int 
    { 
        return $this->id; 
    }

    public function getCart(): ?Cart 
    { 
        return $this->cart; 
    }

    public function setCart(?Cart $cart): static 
    { 
        $this->cart = $cart; 
        return $this; 
    }

    public function getProduct(): ?Product 
    { 
        return $this->product; 
    }

    public function setProduct(?Product $product): static 
    { 
        $this->product = $product; 
        return $this; 
    }

    public function getQuantity(): int 
    { 
        return $this->quantity; 
    }

    public function setQuantity(int $quantity): static
    { 
        $this->quantity = $quantity; 
        return $this; 
    }
}