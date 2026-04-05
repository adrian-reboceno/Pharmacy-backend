<?php
# app/Presentation/DTOs/V1/Category/CategoryListResponseDTO.php

namespace App\Presentation\DTOs\V1\Category;

use App\Domain\Category\Entities\Category as DomainCategory;
use App\Shared\Application\Pagination\PaginatedResult;

final class CategoryListResponseDTO
{
    public function __construct(
        public array $data,
        public array $meta,
    ) {}

    public static function fromPaginatedResult(PaginatedResult $result): self
    {
        $items = $result->items();

        $data = array_map(
            fn (DomainCategory $category) => CategoryResponseDTO::fromEntity($category)->toArray(),
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
