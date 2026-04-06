<?php
# app/Presentation/DTOs/V1/Presentation/PresentationListResponseDTO.php

namespace App\Presentation\DTOs\V1\Presentation;

use App\Domain\Presentation\Entities\Presentation as DomainPresentation;
use App\Shared\Application\Pagination\PaginatedResult;

final class PresentationListResponseDTO
{
    public function __construct(
        public array $data,
        public array $meta,
    ) {}

    public static function fromPaginatedResult(PaginatedResult $result): self
    {
        $items = $result->items();

        $data = array_map(
            fn (DomainPresentation $presentation) => PresentationResponseDTO::fromEntity($presentation)->toArray(),
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