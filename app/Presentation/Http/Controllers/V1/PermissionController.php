<?php

namespace App\Presentation\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Infrastructure\Services\ApiResponseService;
use App\Application\Permission\UseCases\V1\{
    ListPermissions,
    ShowPermission,
    CreatePermission,
    UpdatePermission,
    DeletePermission
};
use App\Application\Permission\DTOs\V1\{
    CreatePermissionDTO,
    UpdatePermissionDTO
};
use App\Domain\Permission\Exceptions\PermissionException;

class PermissionController extends Controller
{
    public function __construct(
        protected ApiResponseService $api,
        protected ListPermissions $list,
        protected ShowPermission $show,
        protected CreatePermission $create,
        protected UpdatePermission $update,
        protected DeletePermission $delete
    ) {}
    public static function middleware(): array
    {
        return [           
            new Middleware('permission:permissions-list', only: ['index']),
            new Middleware('permission:permissions-view', only: ['show']),
            new Middleware('permission:permissions-create', only: ['store']),
            new Middleware('permission:permissions-edit', only: ['update']),
            new Middleware('permission:permissions-delete', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
       
            $perPage   = (int) $request->query('per_page', 10);
            $paginator = $this->list->handle($perPage);

            return $this->api->success(
                $paginator,
                message: 'Lista de permisos obtenida correctamente'
            );
       
    }

    public function show(int $id)
    {
        try {
            $permission = $this->show->handle($id);

            return $this->api->success(
                $permission,
                'Permiso encontrado correctamente',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    public function store(Request $request)
    {
        
        try {
            $dto = new CreatePermissionDTO($request->all());
            $permission = $this->create->handle($dto);
            return $this->api->success($permission, 'Permiso creado correctamente', Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    public function update(Request $request, int $id)
    {
       try {
            $dto = new UpdatePermissionDTO($request->all());
            $permission = $this->update->handle($id, $dto);

            return $this->api->success(
                $permission,
                message: 'Permiso actualizado correctamente',
                code: Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->delete->handle($id);

            return $this->api->success(
                data: [],
                message: 'Permiso eliminado correctamente',
                code: Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }
}
