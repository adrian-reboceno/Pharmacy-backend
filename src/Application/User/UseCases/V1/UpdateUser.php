<?php
# src/Application/User/UseCases/V1/UpdateUser.php

namespace App\Application\User\UseCases\V1;

use App\Application\User\DTOs\V1\UpdateUserDTO;
use App\Domain\User\Entities\User;
use App\Domain\User\Exceptions\InvalidUserValueException;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\UserEmail;
use App\Domain\User\ValueObjects\UserId;
use App\Domain\User\ValueObjects\UserName;
use App\Domain\User\ValueObjects\UserPassword;
use App\Domain\User\ValueObjects\UserRoles;
use App\Shared\Domain\Exceptions\NotFoundException;

/**
 * Use Case: UpdateUser
 *
 * Handles the update process of an existing User entity.
 * Orchestrates data updates based on validated input from the DTO
 * layer and delegates persistence to the domain repository.
 *
 * It ensures that domain integrity is preserved by verifying that
 * the target user exists before applying any modifications.
 */
final class UpdateUser
{
    /**
     * Repository abstraction for persisting and retrieving User entities.
     */
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ) {
    }

    /**
     * Execute the user update process.
     *
     * Flow:
     *  1. Build a UserId value object from the DTO.
     *  2. Fetch the User from the repository.
     *     - If not found, throw NotFoundException.
     *  3. For each non-null field in the DTO, apply the corresponding
     *     change using domain Value Objects.
     *  4. Persist the updated User via the repository.
     *
     * @param UpdateUserDTO $dto
     *        DTO containing the user ID and the fields to update.
     *
     * @return User
     *         Updated User entity.
     *
     * @throws NotFoundException
     *         When no user with the given ID exists.
     *
     * @throws InvalidUserValueException
     *         When any provided value (name, email, password, roles)
     *         violates domain rules enforced by the value objects.
     *
     * @example
     *
     * $dto = new UpdateUserDTO(
     *     id: 42,
     *     name: 'New Name',
     *     email: 'new@example.com',
     *     password: 'new_secure_password',
     *     roles: ['admin', 'editor']
     * );
     *
     * $user = $updateUser->execute($dto);
     * 
     */
    public function execute(UpdateUserDTO $dto): User
    {
        $userId = new UserId($dto->id);

        // Retrieve the user from the repository
        $user = $this->repository->findById($userId);

        if ($user === null) {
            throw new NotFoundException(
                "User with ID {$userId->value()} not found."
            );
        }

        // Apply updates only for provided (non-null) fields

        if ($dto->name !== null) {
            $user->rename(new UserName($dto->name));
        }

        if ($dto->email !== null) {
            $user->changeEmail(new UserEmail($dto->email));
        }

        if ($dto->password !== null) {
            $user->changePassword(UserPassword::fromPlain($dto->password));
        }

        if ($dto->roles !== null) {
            $user->assignRoles(new UserRoles($dto->roles));
        }

        // Persist updated entity
        return $this->repository->save($user);
    }
}
