<?php
# app/Presentation/DTOs/V1/User/UserListResponseDTO.php

namespace App\Presentation\DTOs\V1\User;

use App\Domain\User\Entities\User as DomainUser;
use App\Shared\Application\Pagination\PaginatedResult;

/**
 * UserListResponseDTO
 *
 * DTO para devolver una lista paginada de usuarios en la capa de presentación.
 */
final class UserListResponseDTO
{
    public array $data;
    public array $meta;

    public function __construct(array $data, array $meta)
    {
        $this->data = $data;
        $this->meta = $meta;
    }

    /**
     * Construye el DTO a partir de un PaginatedResult<DomainUser>.
     */
    public static function fromPaginatedResult(PaginatedResult $result): self
    {
        // 1) Obtener items (DomainUser[]) usando los getters de PaginatedResult
        $items = $result->items();   // <— OJO: método, no propiedad

        // 2) Mapear DomainUser → UserResponseDTO → array
        $data = array_map(
            static fn (DomainUser $user) => UserResponseDTO::fromEntity($user)->toArray(),
            $items
        );

        // 3) Meta usando los getters
        $meta = [
            'current_page' => $result->page(),
            'per_page'     => $result->perPage(),
            'total'        => $result->total(),
            'last_page'    => $result->lastPage(),
        ];

        return new self($data, $meta);
    }

    /**
     * @return array{data: array, meta: array}
     */
    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'meta' => $this->meta,
        ];
    }
}
