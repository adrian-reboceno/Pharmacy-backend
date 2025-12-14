<?php
# src/Shared/Domain/Exceptions/NotFoundException.php

namespace App\Shared\Domain\Exceptions;

use RuntimeException;

/**
 * Generic NotFoundException
 *
 * Thrown when a requested domain resource cannot be found.
 * Can be reused across different bounded contexts (User, Role, etc.).
 */
class NotFoundException extends RuntimeException
{
}
