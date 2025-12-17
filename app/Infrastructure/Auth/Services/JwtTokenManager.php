<?php
# app/Infrastructure/Auth/Services/JwtTokenManager.php

namespace App\Infrastructure\Auth\Services;

use App\Domain\Auth\Services\TokenManagerInterface;
use App\Domain\Auth\ValueObjects\AuthToken;
use App\Domain\User\Entities\User as DomainUser;
use App\Infrastructure\User\Models\User as EloquentUser;
use App\Infrastructure\User\Mappers\UserMapper;
use RuntimeException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Infrastructure Service: JwtTokenManager
 *
 * Concrete implementation of TokenManagerInterface using
 * tymon/jwt-auth and the Eloquent User model.
 *
 * Responsibilities:
 *  - Issue JWT tokens for authenticated users.
 *  - Refresh existing tokens.
 *  - Invalidate tokens (logout).
 *  - Resolve the authenticated user from a token.
 *
 * This class lives in the Infrastructure layer and is the only place
 * that knows about JWTAuth and the Eloquent User model.
 */
final class JwtTokenManager implements TokenManagerInterface
{
    /**
     * Issue a new token for the given domain user.
     *
     * @throws RuntimeException If the underlying user model cannot be found or JWT fails.
     */
    public function issueToken(DomainUser $user): AuthToken
    {
        $userId = $user->id()->value();

        $eloquentUser = EloquentUser::find($userId);

        if (! $eloquentUser) {
            throw new RuntimeException("Unable to issue token: user not found (ID {$userId}).");
        }

        try {
            // Generate token from the Eloquent user model
            $token = JWTAuth::fromUser($eloquentUser);
        } catch (JWTException $e) {
            throw new RuntimeException('Unable to issue token.', 0, $e);
        }

        return new AuthToken($token);
    }

    /**
     * Refresh an existing token and return a new one.
     *
     * @throws RuntimeException If the token cannot be refreshed.
     */
    public function refreshToken(AuthToken $token): AuthToken
    {
        try {
            $newToken = JWTAuth::setToken($token->value())->refresh();
        } catch (JWTException $e) {
            throw new RuntimeException('Unable to refresh token.', 0, $e);
        }

        return new AuthToken($newToken);
    }

    /**
     * Invalidate the given token (logout).
     *
     * @throws RuntimeException If the token cannot be invalidated.
     */
    public function invalidateToken(AuthToken $token): void
    {
        try {
            JWTAuth::setToken($token->value())->invalidate();
        } catch (JWTException $e) {
            throw new RuntimeException('Unable to invalidate token.', 0, $e);
        }
    }

    /**
     * Resolve the authenticated user from a token.
     *
     * @throws RuntimeException If authentication fails or the model is invalid.
     */
    public function userFromToken(AuthToken $token): DomainUser
    {
        try {
            $model = JWTAuth::setToken($token->value())->authenticate();
        } catch (JWTException $e) {
            throw new RuntimeException('Unable to resolve user from token.', 0, $e);
        }

        if (! $model instanceof EloquentUser) {
            throw new RuntimeException('Authenticated user is not a valid infrastructure User model.');
        }

        // Map Eloquent model -> Domain User entity
        return UserMapper::toDomain($model);
    }

    /**
     * Optional helper: return token TTL in seconds.
     * (Useful for LoginResultDTO::expiresIn)
     */
    public function ttl(): int
    {
        // tymon/jwt-auth TTL is usually in minutes, so we convert to seconds.
        return JWTAuth::factory()->getTTL() * 60;
    }
}
