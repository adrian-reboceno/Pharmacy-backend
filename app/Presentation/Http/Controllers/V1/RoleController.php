<?php

namespace App\Presentation\Http\Controllers\V1;

use App\Application\Role\DTOs\V1\ListRolesDTO;
use App\Application\Role\DTOs\V1\CreateRoleDTO;
use App\Application\Role\DTOs\V1\UpdateRoleDTO;
use App\Application\Role\UseCases\V1\ListRoles;
use App\Application\Role\UseCases\V1\ShowRole;
use App\Application\Role\UseCases\V1\CreateRole;
use App\Application\Role\UseCases\V1\UpdateRole;
use App\Application\Role\UseCases\V1\DeleteRole;
use App\Http\Controllers\Controller;
use App\Infrastructure\Services\ApiResponseService;
use App\Presentation\DTOs\V1\Role\RoleResponseDTO;
use App\Presentation\DTOs\V1\Role\RoleListResponseDTO;
use App\Presentation\Http\Requests\V1\Role\RoleIndexRequest;
use App\Presentation\Http\Requests\V1\Role\RoleStoreRequest;
use App\Presentation\Http\Requests\V1\Role\RoleUpdateRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller
{
    public function __construct(
        private readonly ListRoles $list,
        private readonly ShowRole $show,
        private readonly CreateRole $create,
        private readonly UpdateRole $update,
        private readonly DeleteRole $delete,
        protected ApiResponseService $api,
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware('permission:roles-list', only: ['index']),
            new Middleware('permission:roles-view', only: ['show']),
            new Middleware('permission:roles-create', only: ['store']),
            new Middleware('permission:roles-edit', only: ['update']),
            new Middleware('permission:roles-delete', only: ['destroy']),
        ];
    }

    // GET /api/v1/roles
    public function index(RoleIndexRequest $request)
    {
        $dto     = ListRolesDTO::fromArray($request->validated());
        $result  = $this->list->execute($dto);
        $payload = RoleListResponseDTO::fromPaginatedResult($result)->toArray();

        return $this->api->success(
            $payload,
            'Role list retrieved successfully'
        );
    }

    // GET /api/v1/roles/{id}
    public function show(int $id)
    {
        try {
            $role = $this->show->execute($id);

            return $this->api->success(
                RoleResponseDTO::fromEntity($role)->toArray(),
                'Role found successfully',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    // POST /api/v1/roles
    public function store(RoleStoreRequest $request)
    {
        try {
            $dto  = CreateRoleDTO::fromArray($request->validated());
            $role = $this->create->execute($dto);

            return $this->api->success(
                RoleResponseDTO::fromEntity($role)->toArray(),
                'Role created successfully',
                Response::HTTP_CREATED
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    // PUT /api/v1/roles/{id}
    public function update(RoleUpdateRequest $request, int $id)
    {
        try {
            $data = array_merge($request->validated(), ['id' => $id]);
            $dto  = UpdateRoleDTO::fromArray($data);

            $role = $this->update->execute($dto);

            return $this->api->success(
                RoleResponseDTO::fromEntity($role)->toArray(),
                'Role updated successfully',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    // DELETE /api/v1/roles/{id}
    public function destroy(int $id)
    {
        try {
            $this->delete->execute($id);

            return $this->api->success(
                [],
                'Role deleted successfully',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }
}
