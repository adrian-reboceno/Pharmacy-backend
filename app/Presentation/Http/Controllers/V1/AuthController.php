<?php
# app/Presentation/Http/Controllers/V1/AuthController.php
namespace App\Presentation\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Presentation\Http\Requests\V1\LoginRequest;
use App\Presentation\Http\Requests\V1\LogoutRequest;
use App\Presentation\Http\Requests\V1\RefreshTokenRequest;
use App\Application\Auth\Services\JwtAuthService;
use App\Presentation\DTOs\V1\AuthUserDTO as userDTO;
use App\Infrastructure\Services\ApiResponseService;
use App\Presentation\Exceptions\V1\Auth\InvalidCredentialsException;
use App\Application\Auth\UseCases\V1\LoginUser;

class AuthController extends Controller
{
    protected JwtAuthService $jwtService;
    protected ApiResponseService $apiResponse;

    public function __construct(JwtAuthService $jwtService, ApiResponseService $apiResponse)
    {
        $this->jwtService = $jwtService;
        $this->apiResponse = $apiResponse;
    }

    /**
     * Login de usuario
     */
   
    public function login(LoginRequest $request, LoginUser $loginUser)
    {
        try {
            $credentials = $request->only(['email', 'password']);
            $data = $loginUser->execute($credentials);
            return $this->apiResponse->success($data, 'Login exitoso');
        } catch (InvalidCredentialsException $e) {
            return $this->apiResponse->error($e->getMessage(), [], 401);
        }
    }

    /**
     * Información del usuario autenticado
     */
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

    /**
     * Logout del usuario
     */
    public function logout(LogoutRequest $request)
    {
        $this->jwtService->logout();
        return $this->apiResponse->success([], 'Logout exitoso');
    }

    /**
     * Refrescar token JWT
     */
    public function refresh(RefreshTokenRequest $request)
    {
        $data = $this->jwtService->refreshToken();
        $userDTO = UserDTO::fromEntity($data['user']);
        $data['user'] = $userDTO->toArray();

        return $this->apiResponse->success($data, 'Token actualizado');
    }
}
