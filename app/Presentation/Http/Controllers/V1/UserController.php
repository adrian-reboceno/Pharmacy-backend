<?php

namespace App\Presentation\Http\Controllers\V1;

use App\Application\User\DTOs\V1\CreateUserDTO;
use App\Application\User\DTOs\V1\UpdateUserDTO;
use App\Application\User\UseCases\V1\CreateUser;
use App\Application\User\UseCases\V1\DeleteUser;
use App\Application\User\UseCases\V1\ListUser;
use App\Application\User\UseCases\V1\ShowUser;
use App\Application\User\UseCases\V1\UpdateUser;
use App\Http\Controllers\Controller;
use App\Infrastructure\Services\ApiResponseService;
use App\Presentation\DTOs\V1\User\UserListResponseDTO;
use App\Presentation\DTOs\V1\User\UserResponseDTO;
use App\Presentation\Http\Requests\V1\User\UserIndexRequest;
use App\Presentation\Http\Requests\V1\User\UserStoreRequest;
// use Illuminate\Http\JsonResponse;
use App\Presentation\Http\Requests\V1\User\UserUpdateRequest;
use Illuminate\Http\Response;

/**
 * Controller: UserController
 *
 * Handles all HTTP interactions for user management (CRUD)
 * in API v1, serving as the entry point from the presentation layer.
 *
 * It transforms validated requests into DTOs, delegates logic to UseCases,
 * and returns structured JSON responses through Presentation DTOs.
 */
class UserController extends Controller
{
    public function __construct(
        private readonly CreateUser $create,
        private readonly UpdateUser $update,
        private readonly DeleteUser $delete,
        private readonly ListUser $list,
        private readonly ShowUser $show,
        protected ApiResponseService $api,
    ) {}

    /**
     * Define middleware permissions for each controller method.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:user-list', only: ['index']),
            new Middleware('permission:user-view', only: ['show']),
            new Middleware('permission:user-create', only: ['store']),
            new Middleware('permission:user-edit', only: ['update']),
            new Middleware('permission:user-delete', only: ['destroy']),
        ];
    }

    // ─────────────────────────────────────────────
    // GET /api/v1/users
    // ─────────────────────────────────────────────
    /**
     * List users (with pagination, search, filters, etc.).
     */
    public function index(UserIndexRequest $request)
    {
        $users = $this->list->handle($request->validated());

        /*return response()->json(
            UserListResponseDTO::fromPaginator($users)->toArray(),
            200
        );*/
        return $this->api->success(
            UserListResponseDTO::fromPaginator($users)->toArray(),
            message: 'User list retrieved successfully'
        );
    }

    // ─────────────────────────────────────────────
    // GET /api/v1/users/{id}
    // ─────────────────────────────────────────────
    /**
     * Retrieve a single user by ID.
     */
    public function show(int $id)
    {

        try {
            $user = $this->show->handle($id);

            return $this->api->success(
                $user,
                'User found successfully',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    // ─────────────────────────────────────────────
    // POST /api/v1/users
    // ─────────────────────────────────────────────
    /**
     * Create a new user.
     */
    public function store(UserStoreRequest $request)
    {
        /*$dto = CreateUserDTO::fromArray($request->validated());
        $user = $this->create->handle($dto);

        return response()->json(
            UserResponseDTO::fromEntity($user),
            201
        );*/
        try {
            $dto = CreateUserDTO::fromArray($request->validated());
            $user = $this->create->handle($dto);

            return $this->api->success(
                UserResponseDTO::fromEntity($user),
                'Role created successfully',
                Response::HTTP_CREATED
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    // ─────────────────────────────────────────────
    // PUT /api/v1/users/{id}
    // ─────────────────────────────────────────────
    /**
     * Update an existing user.
     */
    public function update(UserUpdateRequest $request, int $id)
    {

        /*$dto = UpdateUserDTO::fromArray(array_merge($request->validated()));

        $user = $this->update->handle( $id, $dto,);

        return response()->json(
            UserResponseDTO::fromEntity($user),
            200
        );*/

        try {
            $dto = UpdateUserDTO::fromArray(array_merge($request->validated()));
            $user = $this->update->handle($id, $dto);

            return $this->api->success(
                UserResponseDTO::fromEntity($user),
                message: 'User updated successfully',
                code: Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    // ─────────────────────────────────────────────
    // DELETE /api/v1/users/{id}
    // ─────────────────────────────────────────────
    /**
     * Delete a user by ID.
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
