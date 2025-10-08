<?php
# app/Presentation/DTOs/V1/User/UserListResponseDTO.php

namespace App\Presentation\DTOs\V1\User;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Infrastructure\User\Mappers\UserMapper;

/**
 * UserListResponseDTO
 *
 * Data Transfer Object (DTO) for returning a paginated list of users
 * in the presentation layer. This class transforms paginated Eloquent
 * results into a structured format suitable for API responses, including
 * both user data and pagination metadata.
 *
 * Responsibilities:
 *  - Map each Eloquent User model to a Domain User entity.
 *  - Convert Domain User entities to UserResponseDTOs.
 *  - Provide pagination metadata in a structured array.
 */
final class UserListResponseDTO
{
    /**
     * @var array The list of users as an array of UserResponseDTOs.
     */
    public array $data;

    /**
     * @var array Pagination metadata (current page, per page, total, last page).
     */
    public array $meta;

    /**
     * Constructor: Initialize the DTO with user data and pagination metadata.
     *
     * @param array $data List of users.
     * @param array $meta Pagination metadata.
     */
    public function __construct(array $data, array $meta)
    {
        $this->data = $data;
        $this->meta = $meta;
    }

    /**
     * Transform a paginated Eloquent collection into a UserListResponseDTO.
     *
     * This method:
     *  1. Maps each Eloquent User model to a Domain User entity using UserMapper.
     *  2. Converts each Domain User entity to a UserResponseDTO.
     *  3. Extracts pagination metadata for API response.
     *
     * @param LengthAwarePaginator $paginator Paginated Eloquent users.
     * @return self DTO containing user data and pagination metadata.
     */
    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        $data = $paginator->getCollection()
            ->map(fn($model) => UserResponseDTO::fromEntity(UserMapper::toDomain($model))->toArray())
            ->toArray();

        $meta = [
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
        ];

        return new self($data, $meta);
    }

    /**
     * Convert the DTO to an array suitable for JSON responses.
     *
     * @return array{
     *     data: array,
     *     meta: array
     * }
     */
    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'meta' => $this->meta,
        ];
    }
}
