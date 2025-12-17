<?php

namespace App\Infrastructure\Auth\Services;

use App\Domain\Auth\ValueObjects\AuthToken;
use App\Shared\Domain\Auth\CurrentTokenProviderInterface;
use Tymon\JWTAuth\Facades\JWTAuth;

final class HttpJwtCurrentTokenProvider implements CurrentTokenProviderInterface
{
    public function getCurrentToken(): ?AuthToken
    {
        $raw = JWTAuth::getToken();

        if (! $raw) {
            return null;
        }

        return new AuthToken($raw);
    }
}
