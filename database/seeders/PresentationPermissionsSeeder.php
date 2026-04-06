<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PresentationPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Crear los permisos para "presentation"
        $actions = ['list', 'view', 'create', 'edit', 'delete', 'export', 'print'];

        foreach ($actions as $action) {
            Permission::updateOrCreate(
                ['name' => "presentation-$action", 'guard_name' => 'api'],
                ['name' => "presentation-$action", 'guard_name' => 'api']
            );
        }

        // 2. Obtener el rol "admin"
        $admin = Role::where('name', 'admin')->where('guard_name', 'api')->first();

        // 3. Asignar los permisos creados al rol "admin"
        if ($admin) {
            foreach ($actions as $action) {
                $admin->givePermissionTo("presentation-$action");
            }
        }
    }
}
