<?php
# app/Infrastructure/User/Mappers/UserMapper.php

namespace App\Infrastructure\User\Mappers;

use App\Domain\User\Entities\User as DomainUser;
use App\Domain\User\ValueObjects\UserId;
use App\Domain\User\ValueObjects\UserName;
use App\Domain\User\ValueObjects\UserEmail;
use App\Domain\User\ValueObjects\UserPassword;
use App\Domain\User\ValueObjects\UserRoles;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

/**
 * UserMapper
 *
 * Responsible for converting between Eloquent User models and
 * Domain User entities. This class acts as a bridge between
 * the infrastructure layer (database/Eloquent) and the
 * domain layer (pure business entities).
 *
 * Mapping ensures that domain invariants are preserved
 * and that the Domain layer remains decoupled from
 * any framework-specific implementations.
 */
final class UserMapper
{
    /**
     * Convert an Eloquent User model to a Domain User entity.
     *
     * This method wraps all attributes of the Eloquent model into
     * their corresponding Value Objects defined in the Domain layer.
     *
     * @param Model $model The Eloquent User model instance.
     * @return DomainUser The corresponding Domain User entity.
     */
    public static function toDomain(Model $model): DomainUser
    {
        return new DomainUser(
            new UserId($model->id),
            new UserName($model->name),
            new UserEmail($model->email),
            new UserPassword($model->password),
            new UserRoles($model->roles->pluck('name')->toArray())
        );
    }

    /**
     * Convert a LengthAwarePaginator of Eloquent Users
     * into a LengthAwarePaginator of Domain User entities.
     *
     * This allows paginated results from the database to be
     * seamlessly transformed into domain objects while
     * preserving pagination metadata.
     *
     * @param LengthAwarePaginator $paginator Eloquent paginator.
     * @return LengthAwarePaginator Domain paginator with User entities.
     */
    public static function toDomainPaginator(LengthAwarePaginator $paginator): LengthAwarePaginator
    {
        $items = $paginator->getCollection()->map(fn($model) => self::toDomain($model));

        return new LengthAwarePaginator(
            $items,
            $paginator->total(),
            $paginator->perPage(),
            $paginator->currentPage(),
            ['path' => $paginator->path()]
        );
    }
}
