<?php
#app/presentation/exceptions/v1/auth;
namespace App\Presentation\Exceptions\V1\Auth;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class InvalidCredentialsException extends Exception
{
    protected $code = Response::HTTP_UNAUTHORIZED;

    public function __construct(string $message = "Credenciales inválidas")
    {
        parent::__construct($message, $this->code);
    }
}