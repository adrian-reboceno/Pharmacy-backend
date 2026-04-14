<?php
# app/Presentation/DTOs/V1/UnitOfMeasure/UnitOfMeasureListResponseDTO.php

namespace App\Presentation\DTOs\V1\UnitOfMeasure;

use App\Domain\UnitOfMeasure\Entities\UnitOfMeasure as DomainUnitOfMeasure;
use App\Shared\Application\Pagination\PaginatedResult;

final class UnitOfMeasureListResponseDTO
{
    public function __construct(
        public array $data,
        public array $meta,
    ) {}

    public static function fromPaginatedResult(PaginatedResult $result): self
    {
        $items = $result->items();

        $data = array_map(
            fn (DomainUnitOfMeasure $unitOfMeasure) => UnitOfMeasureResponseDTO::fromEntity($unitOfMeasure)->toArray(),
            $items
        );

        $meta = [
            'current_page' => $result->page(),
            'per_page'     => $result->perPage(),
            'total'        => $result->total(),
            'last_page'    => $result->lastPage(),
        ];

        return new self($data, $meta);
    }

    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'meta' => $this->meta,
        ];
    }
}