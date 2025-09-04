<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // --------------------------------
        // Crear roles con guard 'web'
        // --------------------------------
        $roles = ['admin','cajero','almacen','compras','auditor'];
        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role, 'guard_name' => 'web'],
                ['name' => $role, 'guard_name' => 'web']
            );
        }

        // Obtener roles
        $admin   = Role::where('name','admin')->where('guard_name','web')->firstOrFail();
        $cajero  = Role::where('name','cajero')->where('guard_name','web')->firstOrFail();
        $almacen = Role::where('name','almacen')->where('guard_name','web')->firstOrFail();
        $compras = Role::where('name','compras')->where('guard_name','web')->firstOrFail();
        $auditor = Role::where('name','auditor')->where('guard_name','web')->firstOrFail();

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
            'managercatalog' => ['view'],
            'managerusers' => ['view'],
            'managersuppliers' => ['view'],
            'managerproduct' => ['view'],
            'managerreport' => ['view'],
            'managersales' => ['view'],
            'managershopping' => ['view'],
            'managerrecipes' => ['view'],
            'manageraudits' => ['view'],

        ];

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                Permission::updateOrCreate(
                    ['name' => "$module-$action", 'guard_name' => 'web'],
                    ['name' => "$module-$action", 'guard_name' => 'web']
                );
            }
        }

        // --------------------------------
        // Asignar permisos a roles
        // --------------------------------
        $admin->givePermissionTo(Permission::all());

        $cajero->givePermissionTo([
            'ventas-list','ventas-create','ventas-edit','ventas-imprimir','ventas-anular',
            'devoluciones-list','devoluciones-create','devoluciones-edit','devoluciones-imprimir'
        ]);

        $almacen->givePermissionTo([
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
            ->orWhere('name', 'like', '%imprimir%')->get());

        // --------------------------------
        // Crear usuarios de ejemplo
        // --------------------------------
        $users = [
            ['name'=>'Admin','email'=>'admin@farmacia.com','password'=>'password123','role'=>$admin],
            ['name'=>'Cajero','email'=>'cajero@farmacia.com','password'=>'password123','role'=>$cajero],
            ['name'=>'Almacén','email'=>'almacen@farmacia.com','password'=>'password123','role'=>$almacen],
            ['name'=>'Compras','email'=>'compras@farmacia.com','password'=>'password123','role'=>$compras],
            ['name'=>'Auditor','email'=>'auditor@farmacia.com','password'=>'password123','role'=>$auditor],
        ];

        foreach ($users as $u) {
            $user = User::updateOrCreate(
                ['email'=>$u['email']],
                ['name'=>$u['name'], 'password'=>bcrypt($u['password'])]
            );
            $user->assignRole($u['role']);
        }
    }
}
