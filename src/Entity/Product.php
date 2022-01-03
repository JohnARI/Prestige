<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dialColor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $movement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $braceletType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $strapColor;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $picture;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

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

    public function getDialColor(): ?string
    {
        return $this->dialColor;
    }

    public function setDialColor(string $dialColor): self
    {
        $this->dialColor = $dialColor;

        return $this;
    }

    public function getMovement(): ?string
    {
        return $this->movement;
    }

    public function setMovement(string $movement): self
    {
        $this->movement = $movement;

        return $this;
    }

    public function getBraceletType(): ?string
    {
        return $this->braceletType;
    }

    public function setBraceletType(string $braceletType): self
    {
        $this->braceletType = $braceletType;

        return $this;
    }

    public function getStrapColor(): ?string
    {
        return $this->strapColor;
    }

    public function setStrapColor(string $strapColor): self
    {
        $this->strapColor = $strapColor;

        return $this;
    }

    public function getCategory(): ?category
    {
        return $this->category;
    }

    public function setCategory(?category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }
}
