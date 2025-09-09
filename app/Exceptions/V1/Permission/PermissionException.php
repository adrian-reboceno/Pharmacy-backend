<?php
#app/Exceptions/V1/PermissionException.php

namespace App\Exceptions\V1\Permission;

use Exception;

class PermissionException extends Exception
{
    public static function notFound(int $id): self
    {
        return new self("El permiso con ID {$id} no existe.");
    }

    public static function alreadyExists(string $name): self
    {
        return new self("El permiso '{$name}' ya existe.");
    }
}
