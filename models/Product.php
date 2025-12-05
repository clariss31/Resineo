<?php

class Product extends AbstractEntity
{
    private int $categoryId;
    private string $name;
    private ?string $image = null;
    private ?string $description = null;
    private float $price;
    private ?string $color = null;
    private ?string $scent = null;
    private ?string $toolType = null;

    /**
     * Getters and Setters
     */

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): void
    {
        $this->color = $color;
    }

    public function getScent(): ?string
    {
        return $this->scent;
    }

    public function setScent(?string $scent): void
    {
        $this->scent = $scent;
    }

    public function getToolType(): ?string
    {
        return $this->toolType;
    }

    public function setToolType(?string $toolType): void
    {
        $this->toolType = $toolType;
    }
}
