<?php

// app/Infrastructure/User/Mappers/UserMapper.php

namespace App\Infrastructure\User\Mappers;

use App\Domain\User\Entities\User as DomainUser;
use App\Domain\User\ValueObjects\UserEmail;
use App\Domain\User\ValueObjects\UserId;
use App\Domain\User\ValueObjects\UserName;
use App\Domain\User\ValueObjects\UserPassword;
use App\Domain\User\ValueObjects\UserRoles;
use App\Infrastructure\User\Models\User as EloquentUser;

/**
 * UserMapper
 *
 * Responsible for converting between Eloquent User models and
 * Domain User entities. This class acts as a bridge between
 * the infrastructure layer (database/Eloquent) and the
 * domain layer (pure business entities).
 */
final class UserMapper
{
    /**
     * Convert an Eloquent User model to a Domain User entity.
     *
     * @param  EloquentUser  $model  The Eloquent User model instance.
     */
    public static function toDomain(EloquentUser $model): DomainUser
    {
        return new DomainUser(
            id: new UserId($model->id),
            name: new UserName($model->name),
            email: new UserEmail($model->email),
            // Password in DB is already hashed
            password: UserPassword::fromHash($model->password),
            // Using Spatie's getRoleNames()
            roles: new UserRoles($model->getRoleNames()->toArray())
        );
    }

    /**
     * Convert a Domain User entity to an Eloquent User model.
     *
     * If an existing model instance is provided, it will be updated;
     * otherwise, a new model will be created.
     */
    public static function toEloquent(DomainUser $user, ?EloquentUser $model = null): EloquentUser
    {
        $model ??= new EloquentUser;

        $data = $user->toArray();

        // Only set the ID if present in the domain entity
        if ($data['id'] !== null) {
            $model->id = $data['id'];
        }

        $model->name = $data['name'];
        $model->email = $data['email'];
        $model->password = $data['password']; // already hashed

        return $model;
    }
}
