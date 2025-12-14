<?php

// app/Presentation/DTOs/V1/User/UserResourceCollectionDTO.php

namespace App\Presentation\DTOs\V1\User;

use App\Domain\User\Entities\User as DomainUser;

/**
 * UserResourceCollectionDTO
 *
 * Data Transfer Object (DTO) for returning a collection of users
 * in the presentation layer without pagination. This DTO is suitable
 * for API endpoints that return all users at once or a filtered subset
 * where pagination is not required.
 *
 * Responsibilities:
 *  - Transform a collection of Domain User entities into an array suitable for API responses.
 *  - Include the total count of users in the response.
 *  - Delegate individual user transformation to UserResponseDTO.
 */
final class UserResourceCollectionDTO
{
    /**
     * Convert a collection of users to a structured response array.
     *
     * @param  iterable<DomainUser>  $users  A collection/array of domain User entities.
     * @return array{
     *     data: array<int, array<string, mixed>>, // Transformed list of users
     *     count: int                              // Total number of users in the collection
     * }
     */
    public static function fromUsers(iterable $users): array
    {
        $data = [];
        $count = 0;

        foreach ($users as $user) {
            /** @var DomainUser $user */
            $data[] = UserResponseDTO::fromEntity($user)->toArray();
            $count++;
        }

        return [
            'data' => $data,
            'count' => $count,
        ];
    }
}
