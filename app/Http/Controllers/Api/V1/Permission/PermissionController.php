<?php

namespace App\Http\Controllers\Api\V1\Permission;

use App\Http\Controllers\Controller;
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

    public static function middleware(): array
    {
        return [           
            new Middleware('permission:permissions-list', only: ['index']),
           
        ];
    }

    public function __construct(
        private PermissionService $service
    ) {}

    // LISTAR PERMISOS
    public function index()
    {
        $permissions = $this->service->list();

        return $this->successResponse(
            data: PermissionResource::collection($permissions)->resolve() // convertir a array
        );
    }

    // CREAR PERMISO
    public function store(PermissionStoreRequest $request)
    {
        try {
            $dto = PermissionDTO::fromArray($request->validated());
            $permission = $this->service->create($dto);

            return $this->successResponse(
                message: 'Permiso creado correctamente',
                data: (new PermissionResource($permission))->resolve(),
                status: 201
            );
        } catch (\Throwable $e) {
            return $this->handleException($e, 400);
        }
    }

    // MOSTRAR PERMISO
    public function show(int $id)
    {
        try {
            $permission = $this->service->find($id); // usa el servicio para obtener el permiso

            return $this->successResponse(
                message: 'Permiso encontrado correctamente',
                data: (new PermissionResource($permission))->resolve()
            );
        } catch (\Throwable $e) {
            return $this->handleException($e, 404);
        }
    }

    // ACTUALIZAR PERMISO
    public function update(PermissionUpdateRequest $request, int $id)
    {
        try {
            $dto = PermissionDTO::fromArray($request->validated());
            $permission = $this->service->update($id, $dto);

            return $this->successResponse(
                message: 'Permiso actualizado correctamente',
                data: (new PermissionResource($permission))->resolve()
            );
        } catch (\Throwable $e) {
            return $this->handleException($e, 400);
        }
    }

    // ELIMINAR PERMISO
    public function destroy(int $id)
    {
        try {
            $this->service->delete($id);

            return $this->successResponse(
                message: 'Permiso eliminado correctamente'
            );
        } catch (PermissionException $e) {
            return $this->handleException($e, 404);
        }
    }
}
