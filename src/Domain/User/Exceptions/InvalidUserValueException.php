<?php
# src/Domain/User/Exceptions/InvalidUserValueException.php

namespace App\Domain\User\Exceptions;

use DomainException;

/**
 * Exception: InvalidUserValueException
 *
 * Thrown when a User-related value object receives data
 * that violates domain rules or invariants.
 */
final class InvalidUserValueException extends DomainException
{
}
