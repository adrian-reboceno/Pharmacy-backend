<?php

namespace App\Presentation\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Infrastructure\Services\ApiResponseService;
use App\Application\Role\UseCases\V1\{
    ListRoles,
    ShowRole,
    CreateRole,
    UpdateRole,
    DeleteRole
};
use App\Application\Role\DTOs\V1\{
    CreateRoleDTO,
    UpdateRoleDTO
};

/**
 * Class RoleController
 *
 * Controller responsible for managing Roles via API.
 * It provides endpoints for listing, viewing, creating, updating, and deleting roles.
 * Each action is handled by its corresponding Use Case to keep the business logic separate
 * from the presentation layer.
 *
 * @package App\Presentation\Http\Controllers\V1
 */
class RoleController extends Controller
{
    /**
     * Injects dependencies (Use Cases and API Response service).
     *
     * @param ApiResponseService $api
     * @param ListRoles          $list
     * @param ShowRole           $show
     * @param CreateRole         $create
     * @param UpdateRole         $update
     * @param DeleteRole         $delete
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
     *
     * @return array
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * Example usage in route:
     * ```php
     * GET /api/v1/roles?per_page=15
     * ```
     */
    public function index(Request $request)
    {
        $perPage   = (int) $request->query('per_page', 10);
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
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     *
     * Example usage in route:
     * ```php
     * GET /api/v1/roles/5
     * ```
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * Example usage in route:
     * ```php
     * POST /api/v1/roles
     * {
     *   "name": "Manager",
     *   "guard_name": "api"
     * }
     * ```
     */
    public function store(Request $request)
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
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     *
     * Example usage in route:
     * ```php
     * PUT /api/v1/roles/3
     * {
     *   "name": "Administrator",
     *   "guard_name": "api"
     * }
     * ```
     */
    public function update(Request $request, int $id)
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
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     *
     * Example usage in route:
     * ```php
     * DELETE /api/v1/roles/4
     * ```
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
