<?php
# app/Domain/User/ValueObjects/UserPassword.php

namespace App\Domain\User\ValueObjects;

use App\Domain\User\Exceptions\InvalidUserValueException;

/**
 * Value Object: UserPassword
 *
 * Encapsula la contraseña de un usuario, asegurando que
 * cumpla con las reglas de validación del dominio.
 */
final class UserPassword
{
    private string $password;

    /**
     * Constructor
     *
     * @param string $password Contraseña en texto plano o ya hasheada
     * @throws InvalidUserValueException Si la contraseña es inválida
     */
    public function __construct(string $password)
    {
        if (empty($password)) {
            throw new InvalidUserValueException('Password cannot be empty.');
        }

        $this->password = $password;
    }

    /**
     * Retorna el valor de la contraseña
     *
     * @return string
     */
    public function value(): string
    {
        return $this->password;
    }

    /**
     * Retorna la representación en string
     */
    public function __toString(): string
    {
        return $this->password;
    }
}
