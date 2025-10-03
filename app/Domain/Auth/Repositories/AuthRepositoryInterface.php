<?php
# app/Domain/User/Repositories/AuthRepositoryInterface.php
namespace App\Domain\Auth\Repositories;

use App\Domain\Auth\Entities\User;

interface AuthRepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function getUserPermissions(User $user): array;
    public function getUserRole(User $user): ?string;
}
