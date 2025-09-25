<?php
#app/Application/Permission/UseCases/V1/UpdatePermission.php;
namespace App\Application\Permission\UseCases\V1;


use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Application\Permission\DTOs\V1\UpdatePermissionDTO;
use App\Presentation\DTOs\V1\PermissionDTO;
use Symfony\Component\HttpFoundation\Response; 


class UpdatePermission
{
    public function __construct(protected PermissionRepositoryInterface $repo) {}

    public function handle(int $id, UpdatePermissionDTO $dto): PermissionDTO
    {
        // Verifica si otro permiso ya tiene el mismo name + guard_name
       $existing = $this->repo->exists($dto->name, $dto->guard_name);

        if ($existing) {
            throw new \RuntimeException(
                "Ya existe un permiso con el nombre '{$dto->name}' para el guard '{$dto->guard_name}'.",
                Response::HTTP_CONFLICT
            );
        }

        $permission = $this->repo->update($id, array_filter([
            'name'       => $dto->name,
            'guard_name' => $dto->guard_name,
        ]));

        return PermissionDTO::fromModel($permission);
    }
}

