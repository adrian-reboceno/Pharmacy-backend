<?php
# app/Presentation/DTOs/V1/Role/RoleListResponseDTO.php

namespace App\Presentation\DTOs\V1\Role;

use App\Domain\Role\Entities\Role as DomainRole;
use App\Shared\Application\Pagination\PaginatedResult;

/**
 * DTO para devolver una lista paginada de roles.
 */
final class RoleListResponseDTO
{
    public function __construct(
        public array $data,
        public array $meta,
    ) {
    }

    public static function fromPaginatedResult(PaginatedResult $result): self
    {
        $items = $result->items(); // array<DomainRole>

        $data = array_map(
            fn (DomainRole $role) => RoleResponseDTO::fromEntity($role)->toArray(),
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
