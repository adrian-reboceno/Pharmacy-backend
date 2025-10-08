<?php
# app/Infrastructure/User/Repositories/UserRepository.php

namespace App\Infrastructure\User\Repositories;

use App\Domain\User\Entities\User as DomainUser;
use App\Domain\User\Repositories\UserRepositoryInterface;
//use App\Infrastructure\User\Models\User;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use App\Infrastructure\User\Mappers\UserMapper;

/**
 * Eloquent implementation of UserRepositoryInterface.
 *
 * This repository bridges the Domain layer and Eloquent ORM,
 * mapping database models to domain entities.
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * Find a User by email.
     *
     * @param string $email
     * @return DomainUser|null
     */
    public function findByEmail(string $email): ?DomainUser
    {
        $model = User::where('email', $email)->first();

        return $model ? $this->mapToDomain($model) : null;
    }

    /**
     * Get all roles of a User.
     *
     * @param DomainUser $user
     * @return string[]
     */
    public function getUserRoles(DomainUser $user): array
    {
        $model = User::find($user->id()->value());
        return $model ? $model->roles()->pluck('name')->toArray() : [];
    }

    /**
     * Get all permissions of a User.
     *
     * @param DomainUser $user
     * @return string[]
     */
    public function getUserPermissions(DomainUser $user): array
    {
        $model = User::find($user->id()->value());
        return $model ? $model->getAllPermissions()->pluck('name')->toArray() : [];
    }

    /**
     * Return an Eloquent query builder.
     *
     * @return Builder
     */
    public function query(): Builder
    {
        return User::query();
    }

    /**
     * Find a User by ID.
     *
     * @param int $id
     * @return DomainUser|null
     */
    public function find(int $id): ?DomainUser
    {
        $model = User::with('roles')->find($id);
        return $model ? UserMapper::toDomain($model) : null;
    }
    public function all(): Collection
    {
        return UserMapper::toDomainCollection(User::with('roles')->get());
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return UserMapper::toDomainPaginator(User::with('roles')->paginate($perPage));
    }

    /**
     * Create a new User.
     *
     * @param array $data
     * @return DomainUser
     */
    public function create(array $data): DomainUser
    {
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $model = User::create($data);

        if (!empty($data['roles'])) {
            $model->syncRoles($data['roles']);
        }

        return $this->mapToDomain($model);
    }

    /**
     * Update a User entity.
     *
     * @param DomainUser $user
     * @param array $data
     * @return DomainUser
     */
    public function update(DomainUser $user, array $data): DomainUser
    {
        $model = User::find($user->id()->value());

        if (!$model) {
            throw new \RuntimeException('User not found.');
        }

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $model->update($data);

        if (isset($data['roles'])) {
            $model->syncRoles($data['roles']);
        }

        return $this->mapToDomain($model);
    }

    /**
     * Update a User by ID.
     */
    public function updateById(int $id, array $data): DomainUser
    {
        $user = $this->find($id);
        if (!$user) throw new \RuntimeException('User not found.');
        return $this->update($user, $data);
    }

    /**
     * Delete a User entity.
     */
    public function delete(DomainUser $user): bool
    {
        $model = User::find($user->id()->value());
        return $model ? $model->delete() : false;
    }

    /**
     * Delete a User by ID.
     */
    public function deleteById(int $id): bool
    {
        $user = $this->find($id);
        return $user ? $this->delete($user) : false;
    }

    /**
     * Map Eloquent model to Domain User entity.
     *
     * @param User $model
     * @return DomainUser
     */
    private function mapToDomain(User $model): DomainUser
    {
        return new DomainUser(
            new \App\Domain\User\ValueObjects\UserId($model->id),
            new \App\Domain\User\ValueObjects\UserName($model->name),
            new \App\Domain\User\ValueObjects\UserEmail($model->email),
            new \App\Domain\User\ValueObjects\UserPassword($model->password, true),
            new \App\Domain\User\ValueObjects\UserRoles($model->roles()->pluck('name')->toArray())
        );
    }
}
