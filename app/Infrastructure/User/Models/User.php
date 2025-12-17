<?php
// app/Infrastructure/User/Models/User.php

namespace App\Infrastructure\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Infrastructure Model: User
 *
 * Eloquent implementation of the User persistence model.
 * This class is framework-specific and belongs to the Infrastructure layer.
 * It integrates:
 * - Laravel authentication (Authenticatable)
 * - Spatie roles and permissions (HasRoles)
 * - JWT authentication (JWTSubject via tymon/jwt-auth)
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use HasRoles;
    use Notifiable;

    /**
     * Database table associated with the model.
     */
    protected $table = 'users';

    /**
     * Guard name used by Spatie Permission.
     * Must match the guard used in your auth config (e.g., 'api').
     *
     * @var string
     */
    protected $guard_name = 'api';

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Attributes that should be hidden when serializing.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting definitions.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ─────────────────────────────────────────────
    // JWTSubject implementation
    // ─────────────────────────────────────────────

    /**
     * Get the identifier to be stored in the JWT subject claim.
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return custom claims to be added to the JWT payload.
     *
     * @return array<string, mixed>
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
