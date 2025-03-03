<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(type: "string", length: 255)]
   /* #[Assert\NotBlank(message: "The name of the product can be empty")]
    #[Assert\Length(
       min: 3,
        max: 250,
        minMessage: 'The price of the product can be empty',
        maxMessage: 'The name cannot be longer than {{ limit }} characters',
    )]*/
    private ?string $name = null;


    #[ORM\Column(type: "float")]
    /*#[Assert\NotBlank(message: "The price of the product can be empty")]
    #[Assert\Positive(message: "The price must be greater than 0")]*/
    private ?float $price = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;


    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable(); // Se asigna automáticamente
        $this->updated_at = new \DateTimeImmutable(); // Se asigna automáticamente
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
