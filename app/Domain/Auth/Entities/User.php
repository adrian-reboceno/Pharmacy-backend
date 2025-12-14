<?php

// app/Domain/Auth/Entities/User.php

namespace App\Domain\Auth\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Domain Entity: User
 *
 * Represents the authenticated user within the domain layer.
 * This entity integrates with Laravel’s authentication system,
 * supports role/permission management (via Spatie), and implements
 * JWTSubject for JWT-based authentication.
 */
class User extends Authenticatable implements JWTSubject
{
    use HasRoles;

    /**
     * Domain constructor for the User entity.
     *
     * @param  int  $id  Unique identifier of the user.
     * @param  string  $name  Full name of the user.
     * @param  string  $email  Email address used for authentication.
     * @param  string|null  $role  Optional role assigned to the user.
     * @param  array  $permissions  Array of granted permissions.
     */
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?string $role = null,
        public array $permissions = []
    ) {}

    /**
     * Attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * Attributes that should be hidden when serializing to arrays or JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the identifier to be stored in the JWT subject claim.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return custom claims to be added to the JWT payload.
     *
     * @return array<string, mixed>
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
