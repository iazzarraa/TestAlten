<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ProductInput
{
    #[Assert\NotBlank]
    public string $code;

    #[Assert\NotBlank]
    public string $name;

    public string $description;
    public string $image;
    public string $category;

    #[Assert\PositiveOrZero]
    public float $price;

    #[Assert\PositiveOrZero]
    public int $quantity;

    public string $internalReference;
    public int $shellId;

    #[Assert\Choice(["INSTOCK", "LOWSTOCK", "OUTOFSTOCK"])]
    public string $inventoryStatus;

    #[Assert\Range(min: 0, max: 5)]
    public float $rating;
} 
