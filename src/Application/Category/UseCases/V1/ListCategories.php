<?php
# src/Application/Category/UseCases/V1/ListCategories.php

namespace App\Application\Category\UseCases\V1;

use App\Application\Category\DTOs\V1\ListCategoriesDTO;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Shared\Application\Pagination\PaginatedResult;

final class ListCategories
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categories
    ) {}

    public function execute(ListCategoriesDTO $dto): PaginatedResult
    {
        $items = $this->categories->paginate($dto->page, $dto->perPage, $dto->name);
        $total = $this->categories->count($dto->name);

        return new PaginatedResult(
            items: $items,
            page: $dto->page,
            perPage: $dto->perPage,
            total: $total
        );
    }
}
