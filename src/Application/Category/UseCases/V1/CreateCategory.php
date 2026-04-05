<?php
# src/Application/Category/UseCases/V1/CreateCategory.php

namespace App\Application\Category\UseCases\V1;

use App\Application\Category\DTOs\V1\CreateCategoryDTO;
use App\Domain\Category\Entities\Category;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Domain\Category\ValueObjects\CategoryDescription;
use App\Domain\Category\ValueObjects\CategoryIsActive;
use App\Domain\Category\ValueObjects\CategoryName;
use App\Shared\Domain\Exceptions\AlreadyExistsException;

final class CreateCategory
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categories
    ) {}

    public function execute(CreateCategoryDTO $dto): Category
    {
        $existing = $this->categories->findByName($dto->name);
        if ($existing !== null) {
            throw new AlreadyExistsException('A category with this name already exists.');
        }

        $category = new Category(
            id: null,
            name: new CategoryName($dto->name),
            description: $dto->description !== null
                ? new CategoryDescription($dto->description)
                : null,
            isActive: new CategoryIsActive($dto->isActive),
        );

        return $this->categories->save($category);
    }
}
