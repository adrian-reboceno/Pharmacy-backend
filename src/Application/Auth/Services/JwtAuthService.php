<?php
# src/Application/Auth/Services/JwtAuthService.php

namespace App\Application\Auth\Services;

use App\Domain\Auth\Services\TokenManagerInterface;
use App\Domain\Auth\ValueObjects\AuthToken;
use App\Domain\User\Entities\User;
use App\Shared\Domain\Auth\CurrentTokenProviderInterface;

/**
 * Application Service: JwtAuthService
 *
 * Orchestrates authentication operations (me, logout, refresh)
 * using the domain TokenManagerInterface and an abstract
 * CurrentTokenProviderInterface, without depending on
 * any framework or JWT implementation.
 */
final class JwtAuthService
{
    public function __construct(
        private readonly TokenManagerInterface $tokens,
        private readonly CurrentTokenProviderInterface $currentToken,
    ) {
    }

    /**
     * Get the currently authenticated user, if any.
     */
    public function user(): ?User
    {
        $token = $this->currentToken->getCurrentToken();

        if ($token === null) {
            return null;
        }

        return $this->tokens->userFromToken($token);
    }

    /**
     * Logout the currently authenticated user (invalidate token).
     */
    public function logout(): void
    {
        $token = $this->currentToken->getCurrentToken();

        if ($token !== null) {
            $this->tokens->invalidateToken($token);
        }
    }

    /**
     * Refresh the current token and return new token + user.
     *
     * @return array{
     *     access_token: string,
     *     token_type: string,
     *     expires_in: int,
     *     user: User
     * }
     */
    public function refreshToken(): array
    {
        $token = $this->currentToken->getCurrentToken();

        if ($token === null) {
            throw new \RuntimeException('No authentication token present.');
        }

        $newToken = $this->tokens->refreshToken($token);
        $user     = $this->tokens->userFromToken($newToken);

        $ttl = method_exists($this->tokens, 'ttl')
            ? $this->tokens->ttl()
            : 3600;

        return [
            'access_token' => $newToken->value(),
            'token_type'   => 'bearer',
            'expires_in'   => $ttl,
            'user'         => $user,
        ];
    }
}
