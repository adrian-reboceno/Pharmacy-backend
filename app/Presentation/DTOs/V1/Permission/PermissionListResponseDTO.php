<?php
# app/Presentation/DTOs/V1/Permission/PermissionListResponseDTO.php

namespace App\Presentation\DTOs\V1\Permission;

use App\Domain\Permission\Entities\Permission;
use App\Shared\Application\Pagination\PaginatedResult;

final class PermissionListResponseDTO
{
    public function __construct(
        public array $data,
        public array $meta,
    ) {}

    public static function fromPaginatedResult(PaginatedResult $result): self
    {
        // PaginatedResult<Permission>
        $items = $result->items();      // ← usa el getter, no propiedad
        $total = $result->total();
        $perPage = $result->perPage();
        $page = $result->page();

        $data = array_map(
            fn (Permission $permission) =>
                PermissionResponseDTO::fromEntity($permission)->toArray(),
            $items
        );

        $lastPage = (int) ceil($total / max($perPage, 1));

        $meta = [
            'current_page' => $page,
            'per_page'     => $perPage,
            'total'        => $total,
            'last_page'    => $lastPage,
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
