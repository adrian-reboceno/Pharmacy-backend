<?php

# app/Presentation/DTOs/V1/Laboratory/LaboratoryListResponseDTO.php

namespace App\Presentation\DTOs\V1\Laboratory;

use App\Domain\Laboratory\Entities\Laboratory as DomainLaboratory;
use App\Shared\Application\Pagination\PaginatedResult;

final class LaboratoryListResponseDTO
{
    public function __construct(
        public array $data,
        public array $meta,
    ) {}

    public static function fromPaginatedResult(PaginatedResult $result): self
    {
        $items = $result->items();

        $data = array_map(
            fn (DomainLaboratory $laboratory) => LaboratoryResponseDTO::fromEntity($laboratory)->toArray(),
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