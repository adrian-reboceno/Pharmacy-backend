<?php

// src/Shared/Application/Pagination/PaginatedResult.php

namespace App\Shared\Application\Pagination;

/**
 * Value Object: PaginatedResult
 *
 * Represents a generic paginated result set for the Application layer.
 *
 * @template T
 */
final class PaginatedResult
{
    /**
     * @param  array<int,T>  $items
     */
    public function __construct(
        public readonly array $items,
        public readonly int $total,
        public readonly int $page,
        public readonly int $perPage
    ) {}

    /**
     * Convert to a plain array representation.
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [
            'items' => $this->items,
            'total' => $this->total,
            'page' => $this->page,
            'per_page' => $this->perPage,
        ];
    }
}
