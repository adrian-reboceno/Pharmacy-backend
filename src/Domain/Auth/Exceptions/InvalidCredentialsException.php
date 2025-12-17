<?php
# src/Domain/Auth/Exceptions/InvalidCredentialsException.php

namespace App\Domain\Auth\Exceptions;

use DomainException;

/**
 * Exception: InvalidCredentialsException
 *
 * Thrown when authentication fails due to invalid credentials.
 */
final class InvalidCredentialsException extends DomainException
{
}
