<?php
# src/Application/User/DTOs/V1/ListUsersDTO.php

namespace App\Application\User\DTOs\V1;

/**
 * Data Transfer Object: ListUsersDTO
 *
 * Representa los parámetros de paginación para listar usuarios
 * en la capa de Aplicación.
 */
final class ListUsersDTO
{
    public function __construct(
        public readonly int $page = 1,
        public readonly int $perPage = 15,
    ) {
    }

    /**
     * Crea el DTO a partir de un array (por ejemplo, request->validated()).
     *
     * @param  array  $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            page: (int)($data['page'] ?? 1),
            perPage: (int)($data['per_page'] ?? 15),
        );
    }
}
