<?php

// app/Presentation/DTOs/V1/User/UserListResponseDTO.php

namespace App\Presentation\DTOs\V1\User;

use App\Domain\User\Entities\User as DomainUser;
use App\Shared\Application\Pagination\PaginatedResult;

/**
 * UserListResponseDTO
 *
 * Data Transfer Object (DTO) for returning a paginated list of users
 * in the presentation layer. This class transforms a paginated result
 * of Domain User entities into a structured format suitable for API
 * responses, including both user data and pagination metadata.
 *
 * Responsibilities:
 *  - Convert Domain User entities to UserResponseDTOs.
 *  - Provide pagination metadata in a structured array.
 */
final class UserListResponseDTO
{
    /**
     * @var array<int, array<string, mixed>> The list of users as array payloads.
     */
    public array $data;

    /**
     * @var array<string, int> Pagination metadata (current page, per page, total, last page).
     */
    public array $meta;

    /**
     * Constructor: Initialize the DTO with user data and pagination metadata.
     *
     * @param  array<int, array<string, mixed>>  $data
     * @param  array<string, int>  $meta
     */
    public function __construct(array $data, array $meta)
    {
        $this->data = $data;
        $this->meta = $meta;
    }

    /**
     * Build a UserListResponseDTO from a PaginatedResult of Domain User entities.
     *
     * @param  PaginatedResult<DomainUser>  $paginatedResult
     */
    public static function fromPaginatedResult(PaginatedResult $paginatedResult): self
    {
        $items = $paginatedResult->items();

        $data = array_map(
            static fn (DomainUser $user): array => UserResponseDTO::fromEntity($user)->toArray(),
            $items
        );

        $meta = [
            'current_page' => $paginatedResult->page(),
            'per_page' => $paginatedResult->perPage(),
            'total' => $paginatedResult->total(),
            'last_page' => $paginatedResult->lastPage(),
        ];

        return new self($data, $meta);
    }

    /**
     * Convert the DTO to an array suitable for JSON responses.
     *
     * @return array{
     *     data: array<int, array<string, mixed>>,
     *     meta: array<string, int>
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
