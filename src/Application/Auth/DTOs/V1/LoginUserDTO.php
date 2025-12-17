<?php
# src/Application/Auth/DTOs/V1/LoginUserDTO.php

namespace App\Application\Auth\DTOs\V1;

/**
 * DTO: LoginUserDTO
 *
 * Represents the raw input required to authenticate a user.
 */
final class LoginUserDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {
    }

    /**
     * Create DTO from an associative array (e.g. validated request).
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            email: (string) ($data['email'] ?? ''),
            password: (string) ($data['password'] ?? ''),
        );
    }
}
