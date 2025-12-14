<?php

// app/Domain/User/Exceptions/InvalidUserValueException.php

namespace App\Domain\User\Exceptions;

use DomainException;

/**
 * Exception: InvalidUserValueException
 *
 * Thrown when a Value Object receives invalid or inconsistent data
 * that violates domain rules or invariants.
 */
final class InvalidUserValueException extends DomainException {}
