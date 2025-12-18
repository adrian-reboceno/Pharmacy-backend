<?php

namespace App\Infrastructure\Role\Models;

use Spatie\Permission\Models\Role as SpatieRole;

/**
 * Infrastructure Model: Role
 *
 * Extiende el modelo de Spatie para integrarlo con nuestra
 * estructura de namespaces de infraestructura.
 */
class Role extends SpatieRole
{
    /**
     * Tabla asociada.
     */
    protected $table = 'roles';

    /**
     * Guard a utilizar (debe coincidir con config/permission.php y auth.php).
     */
    protected $guard_name = 'api';

    /**
     * Campos asignables masivamente.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'guard_name',
    ];
}
