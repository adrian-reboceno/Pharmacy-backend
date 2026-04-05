<?php
# src/Application/Category/UseCases/V1/DeleteCategory.php

namespace App\Application\Category\UseCases\V1;

use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Domain\Category\ValueObjects\CategoryId;
use App\Shared\Domain\Exceptions\NotFoundException;

final class DeleteCategory
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categories
    ) {}

    public function execute(int $id): void
    {
        $categoryId = new CategoryId($id);
        $category   = $this->categories->findById($categoryId);

        if ($category === null) {
            throw new NotFoundException("Category with ID {$id} not found.");
        }

        $category->deactivate();
        $this->categories->save($category);
    }
}
