<?php
# src/Shared/Domain/Auth/CurrentTokenProviderInterface.php

namespace App\Shared\Domain\Auth;

use App\Domain\Auth\ValueObjects\AuthToken;

interface CurrentTokenProviderInterface
{
    /**
     * Returns the current AuthToken for this execution context
     * (HTTP request, CLI, etc.), or null if none is present.
     */
    public function getCurrentToken(): ?AuthToken;
}
