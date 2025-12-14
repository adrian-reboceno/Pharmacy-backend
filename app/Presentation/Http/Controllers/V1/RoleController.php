<?php

namespace App\Presentation\Http\Controllers\V1;

use App\Application\Role\DTOs\V1\CreateRoleDTO;
use App\Application\Role\DTOs\V1\UpdateRoleDTO;
use App\Application\Role\UseCases\V1\CreateRole;
use App\Application\Role\UseCases\V1\DeleteRole;
use App\Application\Role\UseCases\V1\ListRoles;
use App\Application\Role\UseCases\V1\ShowRole;
use App\Application\Role\UseCases\V1\UpdateRole;
use App\Http\Controllers\Controller;
use App\Infrastructure\Services\ApiResponseService;
use App\Presentation\Http\Requests\V1\Role\RoleIndexRequest;
use App\Presentation\Http\Requests\V1\Role\RoleStoreRequest;
use App\Presentation\Http\Requests\V1\Role\RoleUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class RoleController
 *
 * Controller responsible for managing Roles via the API.
 * Provides endpoints for listing, viewing, creating, updating, and deleting roles.
 * Each action is delegated to its corresponding Use Case, keeping business logic
 * separated from the presentation layer.
 */
class RoleController extends Controller
{
    /**
     * Inject dependencies (Use Cases and API Response service).
     */
    public function __construct(
        protected ApiResponseService $api,
        protected ListRoles $list,
        protected ShowRole $show,
        protected CreateRole $create,
        protected UpdateRole $update,
        protected DeleteRole $delete
    ) {}

    /**
     * Define middleware permissions for each controller method.
     */
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

    /**
     * GET /roles
     *
     * Retrieve a paginated list of roles.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * Example usage:
     * GET /api/v1/roles?per_page=15
     */
    public function index(RoleIndexRequest $request)
    {
        $perPage = (int) $request->query('per_page', 10);
        $paginator = $this->list->handle($perPage);

        return $this->api->success(
            $paginator,
            message: 'Roles list retrieved successfully'
        );
    }

    /**
     * GET /roles/{id}
     *
     * Retrieve a single role by its ID.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * Example usage:
     * GET /api/v1/roles/5
     */
    public function show(int $id)
    {
        try {
            $role = $this->show->handle($id);

            return $this->api->success(
                $role,
                'Role found successfully',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    /**
     * POST /roles
     *
     * Create a new role from request data.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * Example payload:
     * {
     *   "name": "Manager",
     *   "guard_name": "api",
     *   "permissions": [1,2,3]
     * }
     */
    public function store(RoleStoreRequest $request)
    {
        try {
            $dto = new CreateRoleDTO($request->all());
            $role = $this->create->handle($dto);

            return $this->api->success(
                $role,
                'Role created successfully',
                Response::HTTP_CREATED
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    /**
     * PUT/PATCH /roles/{id}
     *
     * Update an existing role by ID.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * Example payload:
     * {
     *   "name": "Administrator",
     *   "guard_name": "api",
     *   "permissions": [1,2,3]
     * }
     */
    public function update(RoleUpdateRequest $request, int $id)
    {
        try {
            $dto = new UpdateRoleDTO($request->all());
            $role = $this->update->handle($id, $dto);

            return $this->api->success(
                $role,
                message: 'Role updated successfully',
                code: Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    /**
     * DELETE /roles/{id}
     *
     * Delete a role by ID.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * Example usage:
     * DELETE /api/v1/roles/4
     */
    public function destroy(int $id)
    {
        try {
            $this->delete->handle($id);

            return $this->api->success(
                data: [],
                message: 'Role deleted successfully',
                code: Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }
}
