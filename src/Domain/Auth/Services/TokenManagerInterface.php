<?php
# src/Domain/Auth/Services/TokenManagerInterface.php

namespace App\Domain\Auth\Services;

use App\Domain\Auth\ValueObjects\AuthToken;
use App\Domain\User\Entities\User;

/**
 * TokenManagerInterface
 *
 * Abstracts token generation, refresh and invalidation
 * (e.g. JWT) independently from the underlying library.
 */
interface TokenManagerInterface
{
    /**
     * Issue a new token for the given user.
     */
    public function issueToken(User $user): AuthToken;

    /**
     * Refresh an existing token and return a new one.
     */
    public function refreshToken(AuthToken $token): AuthToken;

    /**
     * Invalidate the given token (logout).
     */
    public function invalidateToken(AuthToken $token): void;

    /**
     * Resolve the authenticated user from a token.
     */
    public function userFromToken(AuthToken $token): User;
}
