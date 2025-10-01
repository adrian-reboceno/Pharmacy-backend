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
use App\Presentation\Http\Requests\V1\Permission\{
    PermissionIndexRequest,
    PermissionStoreRequest,
    PermissionUpdateRequest
};

/**
 * Controller responsible for handling HTTP requests related to Permissions.
 *
 * This controller receives incoming API requests and delegates business logic
 * to the corresponding Use Cases.
 *
 * Principles applied:
 * - **SRP (Single Responsibility Principle):** Only handles HTTP request/response for permissions.
 * - **DIP (Dependency Inversion Principle):** Depends on abstractions (Use Cases) rather than concrete implementations.
 *
 * Common routes handled:
 * - GET /permissions             -> index
 * - GET /permissions/{id}        -> show
 * - POST /permissions            -> store
 * - PUT/PATCH /permissions/{id}  -> update
 * - DELETE /permissions/{id}     -> destroy
 */
class PermissionController extends Controller
{
    /**
     * Constructor.
     *
     * Injects the required Use Cases and the API response service.
     *
     * @param ApiResponseService $api Service for standardized API responses
     * @param ListPermissions $list Use Case to list permissions
     * @param ShowPermission $show Use Case to retrieve a single permission
     * @param CreatePermission $create Use Case to create a permission
     * @param UpdatePermission $update Use Case to update a permission
     * @param DeletePermission $delete Use Case to delete a permission
     */
    public function __construct(
        protected ApiResponseService $api,
        protected ListPermissions $list,
        protected ShowPermission $show,
        protected CreatePermission $create,
        protected UpdatePermission $update,
        protected DeletePermission $delete
    ) {}

    /**
     * Define route authorization middleware.
     *
     * @return array Array of middleware definitions
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

    /**
     * List all permissions with optional pagination.
     *
     * @param PermissionIndexRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(PermissionIndexRequest $request)
    {
        $perPage   = (int) $request->query('per_page', 10);
        $paginator = $this->list->handle($perPage);

        return $this->api->success(
            $paginator,
            message: 'Permissions list retrieved successfully'
        );
    }

    /**
     * Retrieve a single permission by its ID.
     *
     * @param int $id Permission ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        try {
            $permission = $this->show->handle($id);

            return $this->api->success(
                $permission,
                'Permission retrieved successfully',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    /**
     * Create a new permission.
     *
     * @param PermissionStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PermissionStoreRequest $request)
    {
        try {
            $dto = new CreatePermissionDTO($request->validated());
            $permission = $this->create->handle($dto);

            return $this->api->success(
                $permission,
                'Permission created successfully',
                Response::HTTP_CREATED
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    /**
     * Update an existing permission by ID.
     *
     * @param PermissionUpdateRequest $request
     * @param int $id Permission ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PermissionUpdateRequest $request, int $id)
    {
        try {
            $dto = new UpdatePermissionDTO($request->validated());
            $permission = $this->update->handle($id, $dto);

            return $this->api->success(
                $permission,
                message: 'Permission updated successfully',
                code: Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    /**
     * Delete a permission by ID.
     *
     * @param int $id Permission ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            $this->delete->handle($id);

            return $this->api->success(
                data: [],
                message: 'Permission deleted successfully',
                code: Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }
}
