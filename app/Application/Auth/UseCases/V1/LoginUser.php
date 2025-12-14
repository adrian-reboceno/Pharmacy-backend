<?php

// app/Application/Auth/UseCases/V1/LoginUser.php

namespace App\Application\Auth\UseCases\V1;

use App\Application\Auth\Services\JwtAuthService;
use App\Presentation\DTOs\V1\AuthUserDTO as UserDTO;
use App\Presentation\Exceptions\V1\Auth\InvalidCredentialsException;

class LoginUser
{
    protected JwtAuthService $jwtService;

    public function __construct(JwtAuthService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    /**
     * Executes the login use case.
     *
     * This method validates the provided user credentials using the
     * authentication service. If successful, it returns the authentication
     * data including a JWT token, user information, roles, and permissions.
     * The user entity is transformed into a Data Transfer Object (DTO) to
     * ensure a clean response format.
     *
     * @param  array  $credentials  An associative array with:
     *                              - 'email' => string
     *                              - 'password' => string
     * @return array An array containing:
     *               - 'token' => string (JWT access token)
     *               - 'user'  => array (user DTO data)
     *               - 'roles' => array
     *               - 'permissions' => array
     *
     * @throws InvalidCredentialsException If authentication fails due to invalid credentials.
     */
    public function execute(array $credentials): array
    {
        // Authenticate via the JWT authentication service
        $data = $this->jwtService->attemptLogin($credentials);

        // Convert the User entity into a DTO for a clean response
        $userDTO = UserDTO::fromEntity($data['user']);
        $data['user'] = $userDTO->toArray();

        return $data; // Includes token, roles, and permissions
    }
}
