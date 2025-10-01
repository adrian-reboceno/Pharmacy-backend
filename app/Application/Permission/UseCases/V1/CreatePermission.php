<?php
# app/Application/Permission/UseCases/V1/CreatePermission.php
namespace App\Application\Permission\UseCases\V1;

use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Application\Permission\DTOs\V1\CreatePermissionDTO;
use App\Presentation\DTOs\V1\PermissionDTO;
use Illuminate\Http\Response;

class CreatePermission
{
    public function __construct(protected PermissionRepositoryInterface $repo) {}

    public function handle(CreatePermissionDTO $dto): PermissionDTO
    {
        // Verificar si ya existe un permiso con ese nombre y guard
        $existing = $this->repo->exists($dto->name, $dto->guard_name);

        if ($existing) {
            throw new \RuntimeException(
                "Ya existe un permiso con el nombre '{$dto->name}' para el guard '{$dto->guard_name}'.",
               409
            );
        }

        $permission = $this->repo->create([
            'name'       => $dto->name,
            'guard_name' => $dto->guard_name,
        ]);

        return PermissionDTO::fromModel($permission);
    }
}
