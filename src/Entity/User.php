<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    #[Groups(['user:read','cart:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Groups(['user:read','cart:read'])]
    private string $email;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:read','cart:read'])]
    private string $username;

    #[ORM\Column(type: 'string')]
    #[Groups(['user:read'])]
    private string $password;

    #[ORM\Column(type: 'string')]
    #[Groups(['user:read','cart:read'])]
    private string $firstname;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Cart::class, cascade: ['persist', 'remove'])]
    #[Groups(['user:read'])]
    private ?Cart $cart = null;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Wishlist::class, cascade: ['persist', 'remove'])]
    #[Groups(['user:read'])]
    private ?Wishlist $wishlist = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
        // No sensitive data to erase
    }

    public function getUserIdentifier(): string
    {
        return $this->email;  
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

    public function getWishlist(): ?Wishlist
    {
        return $this->wishlist;
    }

    public function setWishlist(?Wishlist $wishlist): self
    {
        $this->wishlist = $wishlist;

        return $this;
    }

}
