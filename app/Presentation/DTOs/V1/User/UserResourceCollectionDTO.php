<?php
# app/Presentation/DTOs/V1/User/UserResourceCollectionDTO.php

namespace App\Presentation\DTOs\V1\User;

use Illuminate\Support\Collection;

/**
 * UserResourceCollectionDTO
 *
 * Data Transfer Object (DTO) for returning a collection of users
 * in the presentation layer without pagination. This DTO is suitable
 * for API endpoints that return all users at once or a filtered subset
 * where pagination is not required.
 *
 * Responsibilities:
 *  - Transform a Laravel Collection of users into an array suitable for API responses.
 *  - Include the total count of users in the response.
 *  - Delegate individual user transformation to UserResponseDTO.
 */
final class UserResourceCollectionDTO
{
    /**
     * Convert a collection of users to a structured response array.
     *
     * This method:
     *  1. Maps each user in the collection using UserResponseDTO.
     *  2. Returns the transformed data along with the total count.
     *
     * @param Collection $users A collection of user models or domain entities.
     * @return array{
     *     data: array,   // Transformed list of users
     *     count: int     // Total number of users in the collection
     * }
     */
    public static function fromCollection(Collection $users): array
    {
        return [
            'data' => UserResponseDTO::collection($users),
            'count' => $users->count(),
        ];
    }
}
