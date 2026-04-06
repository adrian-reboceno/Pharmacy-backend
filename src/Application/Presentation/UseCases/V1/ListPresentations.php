<?php
# src/Application/Presentation/UseCases/V1/ListPresentations.php

namespace App\Application\Presentation\UseCases\V1;

use App\Application\Presentation\DTOs\V1\ListPresentationsDTO;
use App\Domain\Presentation\Repositories\PresentationRepositoryInterface;
use App\Shared\Application\Pagination\PaginatedResult;

final class ListPresentations
{
    public function __construct(
        private readonly PresentationRepositoryInterface $presentations
    ) {}

    public function execute(ListPresentationsDTO $dto): PaginatedResult
    {
        $items = $this->presentations->paginate($dto->page, $dto->perPage, $dto->name);
        $total = $this->presentations->count($dto->name);

        return new PaginatedResult(
            items: $items,
            page: $dto->page,
            perPage: $dto->perPage,
            total: $total
        );
    }
}