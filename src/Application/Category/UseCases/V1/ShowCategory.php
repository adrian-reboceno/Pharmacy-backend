<?php
# src/Application/Category/UseCases/V1/ShowCategory.php

namespace App\Application\Category\UseCases\V1;

use App\Domain\Category\Entities\Category;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Domain\Category\ValueObjects\CategoryId;
use App\Shared\Domain\Exceptions\NotFoundException;

final class ShowCategory
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categories
    ) {}

    public function execute(int $id): Category
    {
        $categoryId = new CategoryId($id);
        $category   = $this->categories->findById($categoryId);

        if ($category === null) {
            throw new NotFoundException("Category with ID {$id} not found.");
        }

        return $category;
    }
}
