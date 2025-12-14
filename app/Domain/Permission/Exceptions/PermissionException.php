<?php

namespace App\Domain\Permission\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class PermissionException extends Exception
{
    public function __construct(string $message, int $code)
    {
        parent::__construct($message, $code);
    }

    public static function notFound(int $id): self
    {
        return new self("El permiso con ID {$id} no existe.", Response::HTTP_NOT_FOUND);
    }

    public static function alreadyExists(string $name): self
    {
        return new self("El permiso '{$name}' ya existe.", Response::HTTP_CONFLICT);
    }
}
