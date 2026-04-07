<?php
# src/Application/PharmaceuticalForm/UseCases/V1/ListPharmaceuticalForms.php

namespace App\Application\PharmaceuticalForm\UseCases\V1;

use App\Application\PharmaceuticalForm\DTOs\V1\ListPharmaceuticalFormsDTO;
use App\Domain\PharmaceuticalForm\Repositories\PharmaceuticalFormRepositoryInterface;
use App\Shared\Application\Pagination\PaginatedResult;

final class ListPharmaceuticalForms
{
    public function __construct(
        private readonly PharmaceuticalFormRepositoryInterface $pharmaceuticalForms
    ) {}

    public function execute(ListPharmaceuticalFormsDTO $dto): PaginatedResult
    {
        $items = $this->pharmaceuticalForms->paginate($dto->page, $dto->perPage, $dto->name);
        $total = $this->pharmaceuticalForms->count($dto->name);

        return new PaginatedResult(
            items: $items,
            page: $dto->page,
            perPage: $dto->perPage,
            total: $total
        );
    }
}