<?php
# app/Infrastructure/User/Repositories/UserRepository.php

namespace App\Infrastructure\User\Repositories;

use App\Domain\User\Entities\User as DomainUser;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\UserEmail;
use App\Domain\User\ValueObjects\UserId;
use App\Infrastructure\User\Mappers\UserMapper;
use App\Infrastructure\User\Models\User as EloquentUser;

/**
 * Eloquent implementation of UserRepositoryInterface.
 *
 * This repository bridges the Domain layer and Eloquent ORM,
 * mapping database models to domain entities using UserMapper.
 */
final class UserRepository implements UserRepositoryInterface
{
    /**
     * Find a User by its unique identifier.
     */
    public function findById(UserId $id): ?DomainUser
    {
        $model = EloquentUser::find($id->value());

        return $model ? UserMapper::toDomain($model) : null;
    }

    /**
     * Find a User by email.
     */
    public function findByEmail(UserEmail $email): ?DomainUser
    {
        $model = EloquentUser::where('email', $email->value())->first();

        return $model ? UserMapper::toDomain($model) : null;
    }

    /**
     * Paginate Users.
     *
     * @return DomainUser[]
     */
    public function paginate(int $page, int $perPage): array
    {
        $paginator = EloquentUser::query()
            ->orderBy('id', 'asc')
            ->paginate(
                perPage: $perPage,
                page: $page
            );

        $items = $paginator->items();

        return array_map(
            static fn (EloquentUser $model): DomainUser => UserMapper::toDomain($model),
            $items
        );
    }

    /**
     * Get the total number of Users.
     */
    public function count(): int
    {
        return EloquentUser::count();
    }

    /**
     * Persist a User entity.
     *
     * Creates a new user or updates an existing one, depending
     * on whether the entity has an identifier.
     */
    public function save(DomainUser $user): DomainUser
    {
        $id = $user->id()?->value();

        // If the user already exists, try to load it; otherwise create a new model.
        $model = $id !== null
            ? EloquentUser::find($id) ?? new EloquentUser()
            : new EloquentUser();

        // Map Domain -> Eloquent
        $model = UserMapper::toEloquent($user, $model);
        $model->save();

        // Sync roles using Spatie (if any)
        $roles = $user->roles()->names();
        if (! empty($roles)) {
            $model->syncRoles($roles);
        }

        // Return fresh Domain entity
        return UserMapper::toDomain($model);
    }

    /**
     * Delete a User by its identifier.
     */
    public function delete(UserId $id): void
    {
        EloquentUser::whereKey($id->value())->delete();
    }
}
