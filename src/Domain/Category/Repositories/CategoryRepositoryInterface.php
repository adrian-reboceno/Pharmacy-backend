<?php
# src/Domain/Category/Repositories/CategoryRepositoryInterface.php

namespace App\Domain\Category\Repositories;

use App\Domain\Category\Entities\Category;
use App\Domain\Category\ValueObjects\CategoryId;

interface CategoryRepositoryInterface
{
    public function findById(CategoryId $id): ?Category;

    public function findByName(string $name): ?Category;

    /**
     * @return Category[]
     */
    public function paginate(int $page, int $perPage, ?string $name = null): array;

    public function count(?string $name = null): int;

    public function save(Category $category): Category;

    public function delete(CategoryId $id): void;
}
