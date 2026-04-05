<?php
# src/Application/Laboratory/UseCases/V1/ListLaboratories.php

namespace App\Application\Laboratory\UseCases\V1;

use App\Application\Laboratory\DTOs\V1\ListLaboratoriesDTO;
use App\Domain\Laboratory\Repositories\LaboratoryRepositoryInterface;
use App\Shared\Application\Pagination\PaginatedResult;

final class ListLaboratories
{
    public function __construct(
        private readonly LaboratoryRepositoryInterface $laboratories
    ) {}

    public function execute(ListLaboratoriesDTO $dto): PaginatedResult
    {
        $items = $this->laboratories->paginate($dto->page, $dto->perPage, $dto->name);
        $total = $this->laboratories->count($dto->name);

        return new PaginatedResult(
            items: $items,
            page: $dto->page,
            perPage: $dto->perPage,
            total: $total
        );
    }
}

