<?php

// app/Domain/User/ValueObjects/UserRoles.php

namespace App\Domain\User\ValueObjects;

use App\Domain\User\Exceptions\InvalidUserValueException;
use Illuminate\Support\Collection;

/**
 * Value Object: UserRoles
 *
 * Representa un conjunto de roles asignados a un usuario.
 * Garantiza que todos los roles sean válidos y únicos.
 */
final class UserRoles
{
    /**
     * @var Collection<int, string>
     */
    private Collection $roles;

    /**
     * Constructor
     *
     * @param  array<int,string>  $roles
     *
     * @throws InvalidUserValueException
     */
    public function __construct(array $roles)
    {
        if (empty($roles)) {
            $this->roles = collect();

            return;
        }

        foreach ($roles as $role) {
            if (! is_string($role) || empty($role)) {
                throw new InvalidUserValueException('Invalid role value provided.');
            }
        }

        $this->roles = collect($roles)->unique()->values();
    }

    /**
     * Retorna todos los roles como array de strings
     *
     * @return array<int,string>
     */
    public function names(): array
    {
        return $this->roles->toArray();
    }

    /**
     * Añade un rol y retorna una nueva instancia
     */
    public function add(string $role): self
    {
        $newRoles = $this->roles->push($role)->unique()->values()->toArray();

        return new self($newRoles);
    }

    /**
     * Remueve un rol y retorna una nueva instancia
     */
    public function remove(string $role): self
    {
        $newRoles = $this->roles->reject(fn ($r) => $r === $role)->values()->toArray();

        return new self($newRoles);
    }

    /**
     * Verifica si contiene un rol
     */
    public function contains(string $role): bool
    {
        return $this->roles->contains($role);
    }

    public function __toString(): string
    {
        return implode(', ', $this->roles->toArray());
    }
}
