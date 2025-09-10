<?php

namespace App\Http\Controllers\Api\V1\Permission;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Permission\PermissionIndexRequest;
use App\Http\Requests\V1\Permission\PermissionStoreRequest;
use App\Http\Requests\V1\Permission\PermissionUpdateRequest;
use App\Services\V1\PermissionService;
use App\DTOs\V1\Permission\PermissionDTO;
use App\Exceptions\V1\PermissionException;
use App\Traits\ApiResponseTrait;
use App\Traits\ExceptionHandlerTrait;
use App\Http\Resources\V1\Permission\PermissionResource;


class PermissionController extends Controller
{
    use ApiResponseTrait, ExceptionHandlerTrait;

    

    public function __construct(
        private PermissionService $service
    ) {
        // Middleware dinámico según permisos
        // Middleware dinámico según permisos
        
        // or with specific guard
        // Middleware dinámico según permisos (solo en el constructor)
       
       
    }
    public static function middleware(): array
    {
        return [           
            new Middleware('permission:role-list', only: ['index']),
           
        ];
    }
    
    public function index(PermissionIndexRequest $request)
    {
        $perPage = (int) $request->get('per_page', 15);
        $permissions = $this->service->list($perPage);

        return $this->successResponse(
            data: $permissions->through(fn($perm) => new PermissionResource($perm)),
            message: 'Lista de permisos obtenida correctamente'
        );
    }

    // CREAR PERMISO
    public function store(PermissionStoreRequest $request)
    {
        $dto = PermissionDTO::fromArray($request->validated());
        $permission = $this->service->create($dto);

        return $this->successResponse(
            message: 'Permiso creado correctamente',
            data: (new PermissionResource($permission))->resolve(),
             code: Response::HTTP_CREATED
        );
    }

    // MOSTRAR PERMISO
    public function show(int $id)
    {
        $permission = $this->service->find($id);

        return $this->successResponse(
            message: 'Permiso encontrado correctamente',
            data: (new PermissionResource($permission))->resolve(),
            code: Response::HTTP_OK
        );
    }

    // ACTUALIZAR PERMISO
    public function update(PermissionUpdateRequest $request, int $id)
    {
        $dto = PermissionDTO::fromArray($request->validated());
        $permission = $this->service->update($id, $dto);

        return $this->successResponse(
            message: 'Permiso actualizado correctamente',
            data: (new PermissionResource($permission))->resolve(),
            code: Response::HTTP_OK // 200
        );
    }

    // ELIMINAR PERMISO
    public function destroy(int $id)
    {
        $this->service->delete($id);

        // Al usar 204, no devolvemos "data"
        return response()->json([
                'status'      => 'success',
                'http_status' => Response::$statusTexts[Response::HTTP_NO_CONTENT],
                'message'     => 'Permiso eliminado correctamente',
                'data'        => []
            ], Response::HTTP_NO_CONTENT);
        }

}
