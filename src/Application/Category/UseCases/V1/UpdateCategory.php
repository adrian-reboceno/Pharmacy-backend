<?php
# src/Application/Category/UseCases/V1/UpdateCategory.php

namespace App\Application\Category\UseCases\V1;

use App\Application\Category\DTOs\V1\UpdateCategoryDTO;
use App\Domain\Category\Entities\Category;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Domain\Category\ValueObjects\CategoryDescription;
use App\Domain\Category\ValueObjects\CategoryId;
use App\Domain\Category\ValueObjects\CategoryIsActive;
use App\Domain\Category\ValueObjects\CategoryName;
use App\Shared\Domain\Exceptions\NotFoundException;

final class UpdateCategory
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categories
    ) {}

    public function execute(UpdateCategoryDTO $dto): Category
    {
        $categoryId = new CategoryId($dto->id);
        $category   = $this->categories->findById($categoryId);

        if ($category === null) {
            throw new NotFoundException("Category with ID {$dto->id} not found.");
        }

        if ($dto->name !== null) {
            $category->rename(new CategoryName($dto->name));
        }

        if ($dto->description !== null) {
            $category->changeDescription(
                new CategoryDescription($dto->description)
            );
        }

        if ($dto->isActive !== null) {
            $category->isActive()->value()
                ? $category->deactivate()
                : $category->activate();

            // o si prefieres:
            // $categoryIsActive = new CategoryIsActive($dto->isActive);
            // if ($categoryIsActive->value()) { $category->activate(); } else { $category->deactivate(); }
        }

        return $this->categories->save($category);
    }
}
