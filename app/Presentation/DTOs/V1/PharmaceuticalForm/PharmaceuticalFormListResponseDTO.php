<?php
# app/Presentation/DTOs/V1/PharmaceuticalForm/PharmaceuticalFormListResponseDTO.php

namespace App\Presentation\DTOs\V1\PharmaceuticalForm;

use App\Domain\PharmaceuticalForm\Entities\PharmaceuticalForm as DomainPharmaceuticalForm;
use App\Shared\Application\Pagination\PaginatedResult;

/**
 * DTO para devolver una lista paginada de formas farmacéuticas.
 */
final class PharmaceuticalFormListResponseDTO
{
    public function __construct(
        public array $data,
        public array $meta,
    ) {}

    public static function fromPaginatedResult(PaginatedResult $result): self
    {
        $items = $result->items(); // array<DomainPharmaceuticalForm>

        $data = array_map(
            fn (DomainPharmaceuticalForm $pharmaceuticalForm) => PharmaceuticalFormResponseDTO::fromEntity($pharmaceuticalForm)->toArray(),
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

    /**
     * @return array{data:array,meta:array}
     */
    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'meta' => $this->meta,
        ];
    }
}