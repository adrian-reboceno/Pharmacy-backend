<?php

namespace App\Presentation\Http\Controllers\V1;

use App\Application\User\DTOs\V1\CreateUserDTO;
use App\Application\User\DTOs\V1\UpdateUserDTO;
use App\Application\User\DTOs\V1\ListUsersDTO;

use App\Application\User\UseCases\V1\CreateUser;
use App\Application\User\UseCases\V1\DeleteUser;
use App\Application\User\UseCases\V1\ListUsers;
use App\Application\User\UseCases\V1\ShowUser;
use App\Application\User\UseCases\V1\UpdateUser;

use App\Http\Controllers\Controller;
use App\Infrastructure\Services\ApiResponseService;

use App\Presentation\DTOs\V1\User\UserResponseDTO;
use App\Presentation\DTOs\V1\User\UserListResponseDTO;

use App\Presentation\Http\Requests\V1\User\UserIndexRequest;
use App\Presentation\Http\Requests\V1\User\UserStoreRequest;
use App\Presentation\Http\Requests\V1\User\UserUpdateRequest;

use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller
{
    public function __construct(
        private readonly CreateUser $create,
        private readonly UpdateUser $update,
        private readonly DeleteUser $delete,
        private readonly ListUsers $list,
        private readonly ShowUser $show,
        protected ApiResponseService $api,
    ) {}

    /**
     * Middleware por permiso.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:user-list',   only: ['index']),
            new Middleware('permission:user-view',   only: ['show']),
            new Middleware('permission:user-create', only: ['store']),
            new Middleware('permission:user-edit',   only: ['update']),
            new Middleware('permission:user-delete', only: ['destroy']),
        ];
    }

    // ─────────────────────────────────────────────
    // GET /api/v1/users
    // ─────────────────────────────────────────────
    public function index(UserIndexRequest $request)
    {
        // 1) Construir DTO de aplicación (page, per_page, filtros, etc.)
        $dto = ListUsersDTO::fromArray($request->validated());

        // 2) Ejecutar caso de uso (devuelve PaginatedResult<User>)
        $result = $this->list->execute($dto);

        // 3) Mapear a DTO de presentación
        $responseDto = UserListResponseDTO::fromPaginatedResult($result);

        // 4) Responder
        return $this->api->success(
            $responseDto->toArray(),
            'User list retrieved successfully'
        );
    }

    // ─────────────────────────────────────────────
    // GET /api/v1/users/{id}
    // ─────────────────────────────────────────────
    public function show(int $id)
    {
        try {
            $user = $this->show->execute($id);

            return $this->api->success(
                UserResponseDTO::fromEntity($user)->toArray(),
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
    public function store(UserStoreRequest $request)
    {
        try {
            $dto  = CreateUserDTO::fromArray($request->validated());
            $user = $this->create->execute($dto);

            return $this->api->success(
                UserResponseDTO::fromEntity($user)->toArray(),
                'User created successfully',
                Response::HTTP_CREATED
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    // ─────────────────────────────────────────────
    // PUT /api/v1/users/{id}
    // ─────────────────────────────────────────────
    public function update(UserUpdateRequest $request, int $id)
    {
        try {
            // Agregamos el id al array para que el DTO lo traiga
            $data = array_merge($request->validated(), ['id' => $id]);
            $dto  = UpdateUserDTO::fromArray($data);

            $user = $this->update->execute($dto);

            return $this->api->success(
                UserResponseDTO::fromEntity($user)->toArray(),
                'User updated successfully',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    // ─────────────────────────────────────────────
    // DELETE /api/v1/users/{id}
    // ─────────────────────────────────────────────
    public function destroy(int $id)
    {
        try {
            $this->delete->execute($id);

            return $this->api->success(
                [],
                'User deleted successfully',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }
}
