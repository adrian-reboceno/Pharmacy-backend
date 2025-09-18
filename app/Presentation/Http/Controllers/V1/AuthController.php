<?php
# app/Presentation/Http/Controllers/V1/AuthController.php
namespace App\Presentation\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Presentation\Http\Requests\V1\LoginRequest;
use App\Presentation\Http\Requests\V1\LogoutRequest;
use App\Presentation\Http\Requests\V1\RefreshTokenRequest;
use App\Application\Auth\Services\JwtAuthService;
use App\Presentation\DTOs\V1\UserDTO;
use App\Infrastructure\Services\ApiResponseService;
use App\Presentation\Exceptions\V1\Auth\InvalidCredentialsException;

class AuthController extends Controller
{
    protected JwtAuthService $jwtService;
    protected ApiResponseService $apiResponse;

    public function __construct(JwtAuthService $jwtService, ApiResponseService $apiResponse)
    {
        $this->jwtService = $jwtService;
        $this->apiResponse = $apiResponse;
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only(['email', 'password']);
            $data = $this->jwtService->attemptLogin($credentials);

            $userDTO = UserDTO::fromEntity($data['user']);

            $response = array_merge(
                $this->jwtService->baseTokenResponse($data['token']),
                [
                    'user' => $userDTO->toArray(),
                    'roles' => $data['roles'],
                    'permissions' => $data['permissions'],
                ]
            );

            return $this->apiResponse->success($response, 'Login exitoso');
        } catch (InvalidCredentialsException $e) {
            return $this->apiResponse->error($e->getMessage(), [], 401);
        }
    }

    public function me()
    {
        $user = $this->jwtService->user();
        $userDTO = UserDTO::fromEntity($user);

        return $this->apiResponse->success([
            'user' => $userDTO->toArray(),
            'roles' => $user->role ? [$user->role] : [],
            'permissions' => $user->permissions,
        ], 'Usuario autenticado');
    }

    public function logout(LogoutRequest $request)
    {
        $this->jwtService->logout();
        return $this->apiResponse->success([], 'Logout exitoso');
    }

    public function refresh(RefreshTokenRequest $request)
    {
        $data = $this->jwtService->refreshToken();
        $userDTO = UserDTO::fromEntity($data['user']);

        $response = array_merge(
            $this->jwtService->baseTokenResponse($data['token']),
            [
                'user' => $userDTO->toArray(),
                'roles' => $data['roles'],
                'permissions' => $data['permissions'],
            ]
        );

        return $this->apiResponse->success($response, 'Token actualizado');
    }
}
