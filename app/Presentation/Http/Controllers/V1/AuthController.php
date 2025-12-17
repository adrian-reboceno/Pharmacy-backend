<?php

// app/Presentation/Http/Controllers/V1/AuthController.php

namespace App\Presentation\Http\Controllers\V1;

use App\Application\Auth\DTOs\V1\LoginUserDTO;
use App\Application\Auth\UseCases\V1\LoginUser;
use App\Application\Auth\Services\JwtAuthService;
use App\Domain\Auth\Exceptions\InvalidCredentialsException;
use App\Http\Controllers\Controller;
use App\Infrastructure\Auth\Services\AuthResponseService;
use App\Infrastructure\Services\ApiResponseService;
use App\Presentation\Http\Requests\V1\LoginRequest;
use App\Presentation\Http\Requests\V1\LogoutRequest;
use App\Presentation\Http\Requests\V1\RefreshTokenRequest;

class AuthController extends Controller
{
    public function __construct(
        private readonly JwtAuthService $jwtService,
        private readonly ApiResponseService $apiResponse,
        private readonly AuthResponseService $authResponse,
    ) {}

    /**
     * POST /api/v1/auth/login
     */
    public function login(LoginRequest $request, LoginUser $loginUser)
    {
        try {
            $dto    = LoginUserDTO::fromArray($request->validated());
            $result = $loginUser->execute($dto); // LoginResultDTO

            $payload = $this->authResponse->buildLoginPayload($result);

            return $this->apiResponse->success($payload, 'Login successful');
        } catch (InvalidCredentialsException $e) {
            return $this->apiResponse->error($e->getMessage(), 401);
        }
    }

    /**
     * GET /api/v1/auth/me
     */
    public function me()
    {
        // JwtAuthService debe devolver App\Domain\User\Entities\User
        $domainUser = $this->jwtService->user();

        $payload = $this->authResponse->buildUserProfilePayload($domainUser);

        return $this->apiResponse->success($payload, 'Authenticated user');
    }

    /**
     * POST /api/v1/auth/logout
     */
    public function logout(LogoutRequest $request)
    {
        $this->jwtService->logout();

        return $this->apiResponse->success([], 'Logout successful');
    }

    /**
     * POST /api/v1/auth/refresh
     */
    public function refresh(RefreshTokenRequest $request)
    {
        // Que JwtAuthService::refreshToken devuelva:
        // [
        //   'user'         => DomainUser,
        //   'access_token' => '...',
        //   'token_type'   => 'bearer',
        //   'expires_in'   => 3600,
        // ]
        $data = $this->jwtService->refreshToken();

        $profile = $this->authResponse->buildUserProfilePayload($data['user']);

        $response = [
            'access_token' => $data['access_token'],
            'token_type'   => $data['token_type'],
            'expires_in'   => $data['expires_in'],
            'user'         => $profile['user'],
            'roles'        => $profile['roles'],
            'permissions'  => $profile['permissions'],
        ];

        return $this->apiResponse->success($response, 'Token refreshed');
    }
}
