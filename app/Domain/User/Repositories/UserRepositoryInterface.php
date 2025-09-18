<?php
# app/Domain/User/Repositories/UserRepositoryInterface.php
namespace App\Domain\User\Repositories;

use App\Domain\User\Entities\User;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function getUserPermissions(User $user): array;
    public function getUserRole(User $user): ?string;
}
