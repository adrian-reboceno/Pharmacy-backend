<?php
# src/Application/Auth/DTOs/V1/LoginResultDTO.php

namespace App\Application\Auth\DTOs\V1;

use App\Domain\User\Entities\User as DomainUser;

/**
 * DTO: LoginResultDTO
 *
 * Represents the result of a successful authentication:
 * - Domain User entity
 * - access token
 * - token type
 * - expiration time in seconds
 */
final class LoginResultDTO
{
    public function __construct(
        public readonly DomainUser $user,
        public readonly string $accessToken,
        public readonly int $expiresIn,
        public readonly string $tokenType = 'bearer',
    ) {
    }

    /**
     * Convert to a plain array (useful for controllers / responses).
     *
     * @return array{
     *     user: DomainUser,
     *     access_token: string,
     *     token_type: string,
     *     expires_in: int
     * }
     */
    public function toArray(): array
    {
        return [
            'user'         => $this->user,
            'access_token' => $this->accessToken,
            'token_type'   => $this->tokenType,
            'expires_in'   => $this->expiresIn,
        ];
    }
}
