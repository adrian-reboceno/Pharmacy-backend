<?php
# app/Infrastructure/User/Models/User.php

namespace App\Infrastructure\User\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    // Important: must match your roles guard
    protected $guard_name = 'api';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
