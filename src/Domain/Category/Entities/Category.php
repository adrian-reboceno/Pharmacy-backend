<?php
# src/Domain/Category/Entities/Category.php

namespace App\Domain\Category\Entities;

use App\Domain\Category\ValueObjects\CategoryId;
use App\Domain\Category\ValueObjects\CategoryName;
use App\Domain\Category\ValueObjects\CategoryDescription;
use App\Domain\Category\ValueObjects\CategoryIsActive;

final class Category
{
    public function __construct(
        private ?CategoryId $id,
        private CategoryName $name,
        private ?CategoryDescription $description = null,
        private CategoryIsActive $isActive = new CategoryIsActive(true),
    ) {}

    public function id(): ?CategoryId
    {
        return $this->id;
    }

    public function name(): CategoryName
    {
        return $this->name;
    }

    public function description(): ?CategoryDescription
    {
        return $this->description;
    }

    public function isActive(): CategoryIsActive
    {
        return $this->isActive;
    }

    public function rename(CategoryName $name): void
    {
        $this->name = $name;
    }

    public function changeDescription(?CategoryDescription $description): void
    {
        $this->description = $description;
    }

    public function activate(): void
    {
        $this->isActive = new CategoryIsActive(true);
    }

    public function deactivate(): void
    {
        $this->isActive = new CategoryIsActive(false);
    }
}
