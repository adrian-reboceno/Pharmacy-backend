<?php
# src/Domain/User/Repositories/UserRepositoryInterface.php

namespace App\Domain\User\Repositories;

use App\Domain\User\Entities\User;
use App\Domain\User\ValueObjects\UserEmail;
use App\Domain\User\ValueObjects\UserId;

/**
 * Interface UserRepositoryInterface
 *
 * Defines the contract for user-related persistence operations
 * in the Domain layer, independent of any specific framework
 * or persistence technology.
 *
 * Implementations (Eloquent, raw SQL, external API, in-memory, etc.)
 * must:
 * - Respect User domain invariants.
 * - Work exclusively with domain entities and value objects.
 * - Hide infrastructure-specific details from the domain.
 */
interface UserRepositoryInterface
{
    /**
     * Find a User by its unique identifier.
     *
     * @param UserId $id
     *
     * @return User|null Returns the User entity or null if not found.
     */
    public function findById(UserId $id): ?User;

    /**
     * Find a User by email.
     *
     * @param UserEmail $email
     *
     * @return User|null Returns the User entity or null if not found.
     */
    public function findByEmail(UserEmail $email): ?User;

    /**
     * Paginate Users.
     *
     * @param int $page    Current page number (1-based).
     * @param int $perPage Number of items per page.
     *
     * @return User[] List of User entities for the requested page.
     */
    public function paginate(int $page, int $perPage): array;

    /**
     * Get the total number of Users.
     */
    public function count(): int;

    /**
     * Persist a User entity.
     *
     * Creates a new user or updates an existing one, depending
     * on whether the entity has an identifier.
     *
     * @param User $user
     *
     * @return User The persisted User entity (with ID populated if new).
     */
    public function save(User $user): User;

    /**
     * Delete a User by its identifier.
     *
     * @param UserId $id
     */
    public function delete(UserId $id): void;
}
