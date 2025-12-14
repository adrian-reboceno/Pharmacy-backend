<?php
# app/Infrastructure/User/Models/User.php

namespace App\Infrastructure\User\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasRoles;

    /**
     * Database table associated with the model.
     */
    protected $table = 'users';

    /**
     * Guard name used by Spatie Permission.
     * Must match the guard used in your auth config (e.g., 'api').
     */
    protected string $guard_name = 'api';

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Attributes that should be hidden when serializing.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting definitions.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
