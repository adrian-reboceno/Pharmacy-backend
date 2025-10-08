<?php
# app/Presentation/Exceptions/V1/Auth/InvalidCredentialsException.php

namespace App\Presentation\Exceptions\V1\Auth;

use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class InvalidCredentialsException
 *
 * Custom exception thrown when authentication fails due to invalid credentials.
 *
 * This exception represents an HTTP 401 Unauthorized response and is typically
 * used within the authentication flow when the provided username, email,
 * or password is incorrect.
 *
 * @package App\Presentation\Exceptions\V1\Auth
 */
class InvalidCredentialsException extends Exception
{
    /**
     * The HTTP status code associated with this exception.
     *
     * @var int
     */
    protected $code = Response::HTTP_UNAUTHORIZED;

    /**
     * Creates a new InvalidCredentialsException instance.
     *
     * @param string $message Optional custom error message.
     *                        Defaults to "Invalid credentials".
     */
    public function __construct(string $message = "Invalid credentials")
    {
        parent::__construct($message, $this->code);
    }
}