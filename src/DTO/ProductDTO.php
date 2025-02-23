<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Date;

class ProductDTO
{
    #[Assert\NotBlank(message: "The name of the product can be empty")]
    #[Assert\Length(
        min: 3,
        max: 250,
        minMessage: 'The name must be at least {{ limit }} characters long',
        maxMessage: 'The name cannot be longer than {{ limit }} characters',
    )]
    public string $name;

    #[Assert\NotBlank(message: "The price of the product can be empty")]
    #[Assert\Positive(message: "The price must be greater than 0")]
    public float $price;

    public ?\DateTimeImmutable $updated_at;

    public function __construct(string $name, float $price, ?\DateTimeImmutable $update_at)
    {
        $this->name = $name;
        $this->price = $price;
        $this->updated_at = $update_at;
    }
}
