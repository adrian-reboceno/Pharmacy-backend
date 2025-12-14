<?php

// app/Presentation/Http/Controllers/V1/AuthController.php

namespace App\Presentation\Http\Controllers\V1;

use App\Application\Auth\Services\JwtAuthService;
use App\Application\Auth\UseCases\V1\LoginUser;
use App\Http\Controllers\Controller;
use App\Infrastructure\Services\ApiResponseService;
use App\Presentation\DTOs\V1\AuthUserDTO as UserDTO;
use App\Presentation\Exceptions\V1\Auth\InvalidCredentialsException;
use App\Presentation\Http\Requests\V1\LoginRequest;
use App\Presentation\Http\Requests\V1\LogoutRequest;
use App\Presentation\Http\Requests\V1\RefreshTokenRequest;

/**
 * Controller: AuthController
 *
 * Handles user authentication operations including login, retrieving
 * authenticated user info, logout, and refreshing JWT tokens.
 * Delegates authentication logic to the JwtAuthService and LoginUser use case,
 * and formats responses through ApiResponseService.
 */
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
     * Authenticate a user and return a JWT token.
     *
     * Accepts email and password credentials, validates them through the
     * LoginUser use case, and responds with token details, user data,
     * roles, and permissions. Returns an error if credentials are invalid.
     *
     * @param  LoginRequest  $request  Validated login request containing 'email' and 'password'.
     * @param  LoginUser  $loginUser  Use case handling the login process.
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request, LoginUser $loginUser)
    {
        try {
            $credentials = $request->only(['email', 'password']);
            $data = $loginUser->execute($credentials);

            return $this->apiResponse->success($data, 'Login successful');
        } catch (InvalidCredentialsException $e) {
            return $this->apiResponse->error($e->getMessage(), 401);
        }
    }

    /**
     * Retrieve the currently authenticated user information.
     *
     * Returns user data as a DTO, along with roles and permissions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = $this->jwtService->user();
        $userDTO = UserDTO::fromEntity($user);

        return $this->apiResponse->success([
            'user' => $userDTO->toArray(),
            'roles' => $user->role ? [$user->role] : [],
            'permissions' => $user->permissions,
        ], 'Authenticated user');
    }

    /**
     * Log out the authenticated user.
     *
     * Invalidates the current JWT token and clears the user session.
     *
     * @param  LogoutRequest  $request  Validated logout request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(LogoutRequest $request)
    {
        $this->jwtService->logout();

        return $this->apiResponse->success([], 'Logout successful');
    }

    /**
     * Refresh the JWT token for the authenticated user.
     *
     * Issues a new token and returns updated user, roles, and permissions.
     *
     * @param  RefreshTokenRequest  $request  Validated refresh token request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(RefreshTokenRequest $request)
    {
        $data = $this->jwtService->refreshToken();
        $userDTO = UserDTO::fromEntity($data['user']);
        $data['user'] = $userDTO->toArray();

        return $this->apiResponse->success($data, 'Token refreshed');
    }
}
