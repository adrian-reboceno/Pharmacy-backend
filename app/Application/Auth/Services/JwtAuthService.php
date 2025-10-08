<?php
# app/Application/Auth/Services/JwtAuthService.php

namespace App\Application\Auth\Services;

use App\Domain\Auth\Repositories\AuthRepositoryInterface;
use App\Domain\Auth\Entities\User as UserEntity;
use App\Presentation\Exceptions\V1\Auth\InvalidCredentialsException;
use App\Presentation\Exceptions\V1\Auth\UserNotAuthenticatedException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class JwtAuthService
{
    protected ?UserEntity $user = null;
    protected AuthRepositoryInterface $AuthRepository;

    public function __construct(AuthRepositoryInterface $AuthRepository)
    {
        $this->AuthRepository = $AuthRepository;
    }

    /**
     * Attempts to log in with the provided credentials.
     *
     * If successful, it generates a JWT token and returns it along with
     * user information, roles, and permissions. If authentication fails,
     * an exception is thrown.
     *
     * @param array $credentials Associative array containing:
     *                           - 'email'    => string
     *                           - 'password' => string
     *
     * @return array Authentication response including:
     *               - 'access_token' => string
     *               - 'token_type'   => string ("bearer")
     *               - 'expires_in'   => int (token TTL in seconds)
     *               - 'user'         => UserEntity
     *               - 'roles'        => array
     *               - 'permissions'  => array
     *               - 'message'      => string
     *
     * @throws InvalidCredentialsException If the provided credentials are invalid.
     */
    public function attemptLogin(array $credentials): array
    {
        if (! $token = Auth::attempt($credentials)) {
            throw new InvalidCredentialsException('Invalid credentials.');
        }

        $this->user = $this->AuthRepository->findByEmail(Auth::user()->email);

        return $this->respondWithToken($token);
    }

    /**
     * Returns the authenticated User entity.
     *
     * If the user is not yet loaded, it attempts to load it from the
     * authentication guard. Throws an exception if no user is authenticated.
     *
     * @return UserEntity
     *
     * @throws UserNotAuthenticatedException If there is no authenticated user.
     * @throws \RuntimeException If the User entity cannot be loaded.
     */
    public function user(): UserEntity
    {
        if ($this->user) {
            return $this->user;
        }

        $eloquentUser = Auth::user();

        if (!$eloquentUser) {
            throw new UserNotAuthenticatedException('No authenticated user found.');
        }

        $this->user = $this->AuthRepository->findByEmail($eloquentUser->email);

        if (!$this->user) {
            throw new \RuntimeException('Failed to load User entity.');
        }

        return $this->user;
    }

    /**
     * Logs out the current user and invalidates the active JWT token.
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::logout();
        $this->user = null;
    }

    /**
     * Refreshes the current JWT token.
     *
     * Returns a new authentication response with the refreshed token,
     * along with the user entity, roles, and permissions.
     *
     * @return array
     */
    public function refreshToken(): array
    {
        $token = Auth::refresh();
        $this->user = $this->AuthRepository->findByEmail(Auth::user()->email);

        return $this->respondWithToken($token);
    }

    /**
     * Creates a minimal JWT token containing only the user_id claim.
     *
     * Useful for lightweight operations or frontend initialization.
     *
     * @param UserEntity $user The domain User entity.
     *
     * @return string The generated JWT token.
     */
    public function createMinimalToken(UserEntity $user): string
    {
      //  $eloquentUser = new \App\Models\User(['id' => $user->id]); use App\Infrastructure\User\Models\User
      $eloquentUser = new  \App\Models\User(['id' => $user->id]); 
        return JWTAuth::claims([])->fromUser($eloquentUser);
    }

    /**
     * Builds the authentication response payload.
     *
     * The response contains the JWT token, its type, expiration time,
     * user information, roles, permissions, and an optional message.
     *
     * @param string $token             The generated JWT token.
     * @param string $message           Optional response message (default: "Authentication successful").
     * @param bool   $includeRoles      Whether to include user roles in the response.
     * @param bool   $includePermissions Whether to include user permissions in the response.
     *
     * @return array Authentication response.
     */
    public function respondWithToken(
        string $token,
        string $message = 'Authentication successful',
        bool $includeRoles = true,
        bool $includePermissions = true
    ): array {
        $user = $this->user ?? $this->user();

        $roles = $includeRoles ? ($user->role ? [$user->role] : []) : [];
        $permissions = $includePermissions ? $user->permissions : [];

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'user' => $user,
            'roles' => $roles,
            'permissions' => $permissions,
            'message' => $message,
        ];
    }
}
