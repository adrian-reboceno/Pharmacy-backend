<?php

// src/Application/User/DTOs/V1/ListUsersDTO.php

namespace App\Application\User\DTOs\V1;

/**
 * Data Transfer Object: ListUsersDTO
 *
 * Represents the input parameters used to list Users,
 * typically for pagination.
 */
final class ListUsersDTO
{
    /**
     * @param  int  $page  Current page number (1-based).
     * @param  int  $perPage  Number of items per page.
     */
    public function __construct(
        public readonly int $page = 1,
        public readonly int $perPage = 20
    ) {}

    /**
     * Create a new DTO instance from an associative array.
     *
     * @param  array<string,mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $page = isset($data['page']) ? (int) $data['page'] : 1;
        $perPage = isset($data['per_page']) ? (int) $data['per_page'] : 20;

        if ($page < 1) {
            $page = 1;
        }

        if ($perPage < 1) {
            $perPage = 20;
        }

        return new self(
            page: $page,
            perPage: $perPage
        );
    }

    /**
     * Convert the DTO into a plain array representation.
     *
     * @return array<string,int>
     */
    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'per_page' => $this->perPage,
        ];
    }
}
