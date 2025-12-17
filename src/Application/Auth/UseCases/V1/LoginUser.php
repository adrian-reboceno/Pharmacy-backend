<?php
# app/Application/Auth/UseCases/V1/LoginUser.php

namespace App\Application\Auth\UseCases\V1;

use App\Application\Auth\DTOs\V1\LoginUserDTO;
use App\Application\Auth\DTOs\V1\LoginResultDTO;
use App\Domain\Auth\Exceptions\InvalidCredentialsException;
use App\Domain\Auth\Services\TokenManagerInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\UserEmail;

/**
 * Use Case: LoginUser
 *
 * Handles user authentication given email and password.
 *
 * Responsibilities:
 *  - Validate credentials format via Value Objects.
 *  - Retrieve the user from the UserRepository.
 *  - Verify the password using domain rules.
 *  - Issue an authentication token via TokenManagerInterface.
 */
final class LoginUser
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
        private readonly TokenManagerInterface $tokens,
    ) {
    }

    /**
     * Execute the login process.
     *
     * @param  LoginUserDTO  $dto  DTO containing raw credentials.
     *
     * @return LoginResultDTO
     *
     * @throws InvalidCredentialsException When the email or password are invalid.
     */
    public function execute(LoginUserDTO $dto): LoginResultDTO
    {
        // 1. Build email VO
        $emailVo = new UserEmail($dto->email);

        // 2. Find user by email
        $user = $this->users->findByEmail($emailVo);

        if ($user === null) {
            throw new InvalidCredentialsException('Invalid email or password.');
        }

        // 3. Verify password using the value object
        if (! $user->password()->verify($dto->password)) {
            throw new InvalidCredentialsException('Invalid email or password.');
        }

        // 4. Issue auth token from the domain token manager
        $authToken = $this->tokens->issueToken($user);

        // 5. TTL (time to live) for the token
        $ttl = method_exists($this->tokens, 'ttl')
            ? $this->tokens->ttl()
            : 3600; // default 1 hour

        return new LoginResultDTO(
            user: $user,
            accessToken: $authToken->value(),
            expiresIn: $ttl,
            tokenType: 'bearer',
        );
    }
}
