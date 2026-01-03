<?php

namespace App\Presentation\Http\Controllers\V1;

use App\Application\Permission\DTOs\V1\CreatePermissionDTO;
use App\Application\Permission\DTOs\V1\UpdatePermissionDTO;
use App\Application\Permission\DTOs\V1\ListPermissionsDTO;
use App\Application\Permission\UseCases\V1\CreatePermission;
use App\Application\Permission\UseCases\V1\DeletePermission;
use App\Application\Permission\UseCases\V1\ListPermissions;
use App\Application\Permission\UseCases\V1\ShowPermission;
use App\Application\Permission\UseCases\V1\UpdatePermission;
use App\Http\Controllers\Controller;
use App\Infrastructure\Services\ApiResponseService;
use App\Presentation\DTOs\V1\Permission\PermissionResponseDTO;
use App\Presentation\DTOs\V1\Permission\PermissionListResponseDTO;
use App\Presentation\Http\Requests\V1\Permission\PermissionIndexRequest;
use App\Presentation\Http\Requests\V1\Permission\PermissionStoreRequest;
use App\Presentation\Http\Requests\V1\Permission\PermissionUpdateRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller
{
    public function __construct(
        protected ApiResponseService $api,
        private readonly ListPermissions $list,
        private readonly ShowPermission $show,
        private readonly CreatePermission $create,
        private readonly UpdatePermission $update,
        private readonly DeletePermission $delete,
    ) {}

    /**
     * Define route authorization middleware.
     */
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

    // ─────────────────────────────────────────────
    // GET /api/v1/permissions
    // ─────────────────────────────────────────────
    public function index(PermissionIndexRequest $request)
    {
        // 1) Construir DTO de aplicación (page, per_page, filtros, etc.)
        $dto = ListPermissionsDTO::fromArray($request->validated());

        // 2) Ejecutar caso de uso (devuelve PaginatedResult<Permission>)
        $result = $this->list->execute($dto);

        // 3) Mapear a DTO de presentación
        $responseDto = PermissionListResponseDTO::fromPaginatedResult($result);

        // 4) Responder
        return $this->api->success(
            $responseDto->toArray(),
            'Permissions list retrieved successfully'
        );
    }

    public function search(PermissionIndexRequest $request)
    {
        $dto    = ListPermissionsDTO::fromArray($request->validated());
        $result = $this->list->execute($dto);
        $responseDto = PermissionListResponseDTO::fromPaginatedResult($result);

        return $this->api->success(
            $responseDto->toArray(),
            'Permissions list retrieved successfully'
        );
    }
    // ─────────────────────────────────────────────
    // GET /api/v1/permissions/{id}
    // ─────────────────────────────────────────────
    public function show(int $id)
    {
        try {
            $id = (int) $id;
            // 1) el caso de uso devuelve Domain\Permission\Entities\Permission
            $permission = $this->show->execute($id);

            // 2) lo mapeas a DTO de respuesta
            $responseDto = PermissionResponseDTO::fromEntity($permission);

            return $this->api->success(
                $responseDto->toArray(),
                'Permission found successfully',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    // ─────────────────────────────────────────────
    // POST /api/v1/permissions
    // ─────────────────────────────────────────────
    public function store(PermissionStoreRequest $request)
    {
        try {
            $dto        = CreatePermissionDTO::fromArray($request->validated());
            $permission = $this->create->execute($dto);

            return $this->api->success(
                PermissionResponseDTO::fromEntity($permission)->toArray(),
                'Permission created successfully',
                Response::HTTP_CREATED
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    // ─────────────────────────────────────────────
    // PUT /api/v1/permissions/{id}
    // ─────────────────────────────────────────────
    public function update(PermissionUpdateRequest $request, int $id)
    {
        try {
            // si tu UpdatePermissionDTO no lleva id, se lo inyectamos
            $data = array_merge($request->validated(), ['id' => $id]);
            $dto  = UpdatePermissionDTO::fromArray($data);

            $permission = $this->update->execute( $dto);

            return $this->api->success(
                PermissionResponseDTO::fromEntity($permission)->toArray(),
                'Permission updated successfully',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    // ─────────────────────────────────────────────
    // DELETE /api/v1/permissions/{id}
    // ─────────────────────────────────────────────
    public function destroy(int $id)
    {
        try {
            $this->delete->execute($id);

            return $this->api->success(
                [],
                'Permission deleted successfully',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }
}
