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
    private $matter;

    /**
     * @ORM\Column(type="integer")
     */
    private $length;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $braceletType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $strapColor;

    /**
     * @ORM\ManyToOne(targetEntity=category::class, inversedBy="products")
     */
    private $category;

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

    public function getMatter(): ?string
    {
        return $this->matter;
    }

    public function setMatter(string $matter): self
    {
        $this->matter = $matter;

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(int $length): self
    {
        $this->length = $length;

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
}
