<?php
# src/Application/UnitOfMeasure/UseCases/V1/ListUnitOfMeasure.php

namespace App\Application\UnitOfMeasure\UseCases\V1;

use App\Application\UnitOfMeasure\DTOs\V1\ListUnitOfMeasuresDTO;
use App\Domain\UnitOfMeasure\Repositories\UnitOfMeasureRepositoryInterface;
use App\Shared\Application\Pagination\PaginatedResult;

final class ListUnitOfMeasure
{
    public function __construct(
        private readonly UnitOfMeasureRepositoryInterface $unitsOfMeasure
    ) {}

    public function execute(ListUnitOfMeasuresDTO $dto): PaginatedResult
    {
        $items = $this->unitsOfMeasure->paginate($dto->page, $dto->perPage, $dto->name);
        $total = $this->unitsOfMeasure->count($dto->name);

        return new PaginatedResult(
            items: $items,
            page: $dto->page,
            perPage: $dto->perPage,
            total: $total
        );
    }
}