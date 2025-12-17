<?php

namespace Database\Seeders;

//use App\Models\User;
use App\Infrastructure\User\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // --------------------------------
        // Crear roles con guard 'web'
        // --------------------------------
        $roles = ['admin', 'cajero', 'almacen', 'compras', 'auditor'];
        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role, 'guard_name' => 'api'],
                ['name' => $role, 'guard_name' => 'api']
            );
        }

        // Obtener roles
        $admin = Role::where('name', 'admin')->where('guard_name', 'api')->firstOrFail();
        $cajero = Role::where('name', 'cajero')->where('guard_name', 'api')->firstOrFail();
        $almacen = Role::where('name', 'almacen')->where('guard_name', 'api')->firstOrFail();
        $compras = Role::where('name', 'compras')->where('guard_name', 'api')->firstOrFail();
        $auditor = Role::where('name', 'auditor')->where('guard_name', 'api')->firstOrFail();

        // --------------------------------
        // Definir permisos CRUD + subpermisos por módulo
        // --------------------------------
        /*$modules = [
            'medicamento'    => ['list','create','edit','delete','exportar','imprimir'],
            'usuarios'       => ['list','create','edit','delete','exportar','imprimir'],
            'catalogos'      => ['list','create','edit','delete','exportar','imprimir'],
            'precios'        => ['list','create','edit','delete','exportar'],
            'ventas'         => ['list','create','edit','delete','imprimir','anular'],
            'devoluciones'   => ['list','create','edit','delete','imprimir','autorizar'],
            'recepciones'    => ['list','create','edit','delete','imprimir'],
            'transferencias' => ['list','create','edit','delete','imprimir'],
            'ajustes'        => ['list','create','edit','delete','imprimir'],
            'ordenes-compra' => ['list','create','edit','delete','imprimir','aprobar'],
            'proveedores'    => ['list','create','edit','delete','exportar'],
            'costos'         => ['list','create','edit','delete','exportar'],
            'reportes'       => ['list','exportar','imprimir'],
            'reportes'       => ['list','exportar','imprimir'],
        ];*/

        $modules = [
            'manager' => ['dashboards', 'catalogs', 'users', 'suppliers', 'products', 'reports', 'sales', 'shoppings', 'recipes', 'audits', 'permissions'],
            'user' => ['list', 'view', 'create', 'edit', 'delete', 'export', 'print'],
            'roles' => ['list', 'view', 'create', 'edit', 'delete', 'export', 'print'],
            'permissions' => ['list', 'view', 'create', 'edit', 'delete', 'export', 'print'],
            'category' => ['list', 'view', 'create', 'edit', 'delete', 'export', 'print'],
            'status' => ['list', 'view', 'create', 'edit', 'delete', 'export', 'print'],
            'denominations' => ['list', 'view', 'create', 'edit', 'delete', 'export', 'print'],
            'laboratories' => ['list', 'view', 'create', 'edit', 'delete', 'export', 'print'],
            'saletypes' => ['list', 'view', 'create', 'edit', 'delete', 'export', 'print'],
            'pharmaceuticalforms' => ['list', 'view', 'create', 'edit', 'delete', 'export', 'print'],
            'symptoms' => ['list', 'view', 'create', 'edit', 'delete', 'export', 'print'],
            'suppliers' => ['list', 'view', 'create', 'edit', 'delete', 'export', 'print'],
            'products' => ['list', 'view', 'create', 'edit', 'delete', 'export', 'print'],
            'batches' => ['list', 'view', 'create', 'edit', 'delete', 'export', 'print'],
            'sales' => ['list', 'view', 'create', 'edit', 'delete', 'print', 'annular'],
            'returns' => ['list', 'view', 'create', 'edit', 'delete', 'print', 'authorize'],
            'report' => ['list', 'view', 'export', 'print'],

        ];

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                Permission::updateOrCreate(
                    ['name' => "$module-$action", 'guard_name' => 'api'],
                    ['name' => "$module-$action", 'guard_name' => 'api']
                );
            }
        }

        // --------------------------------
        // Asignar permisos a roles
        // --------------------------------
        $admin->givePermissionTo(Permission::all());

        $cajero->givePermissionTo([
            'sales-view', 'sales-create', 'sales-edit', 'sales-print', 'sales-annular',
            'returns-view', 'returns-create', 'returns-edit', 'returns-print',
        ]);

        /*$almacen->givePermissionTo([
            'recepciones-list','recepciones-create','recepciones-edit','recepciones-imprimir',
            'transferencias-list','transferencias-create','transferencias-edit','transferencias-imprimir',
            'ajustes-list','ajustes-create','ajustes-edit','ajustes-imprimir'
        ]);

        $compras->givePermissionTo([
            'ordenes-compra-list','ordenes-compra-create','ordenes-compra-edit','ordenes-compra-imprimir','ordenes-compra-aprobar',
            'proveedores-list','proveedores-create','proveedores-edit','proveedores-exportar',
            'costos-list','costos-create','costos-edit','costos-exportar'
        ]);

        $auditor->givePermissionTo(Permission::where('name', 'like', '%list%')
            ->orWhere('name', 'like', '%exportar%')
            ->orWhere('name', 'like', '%imprimir%')->get()); */

        // --------------------------------
        // Crear usuarios de ejemplo
        // --------------------------------
        $users = [
            ['name' => 'Admin', 'email' => 'admin@farmacia.com', 'password' => 'password123', 'role' => $admin],
            ['name' => 'Cajero', 'email' => 'cajero@farmacia.com', 'password' => 'password123', 'role' => $cajero],
            ['name' => 'Almacén', 'email' => 'almacen@farmacia.com', 'password' => 'password123', 'role' => $almacen],
            ['name' => 'Compras', 'email' => 'compras@farmacia.com', 'password' => 'password123', 'role' => $compras],
            ['name' => 'Auditor', 'email' => 'auditor@farmacia.com', 'password' => 'password123', 'role' => $auditor],

        ];

        foreach ($users as $u) {
            $user = User::updateOrCreate(
                ['email' => $u['email']],
                ['name' => $u['name'], 'password' => bcrypt($u['password'])]
            );
            $user->assignRole($u['role']);
        }
    }
}
