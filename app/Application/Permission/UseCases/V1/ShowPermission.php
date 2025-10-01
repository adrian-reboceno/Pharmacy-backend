<?php
# app/Application/Permission/UseCases/V1/ShowPermission.php
namespace App\Application\Permission\UseCases\V1;

use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Presentation\DTOs\V1\PermissionDTO;
use Illuminate\Http\Response;


class ShowPermission
{
    public function __construct(protected PermissionRepositoryInterface $repo) {}

    public function handle(int $id): PermissionDTO
    {
        $permission = $this->repo->find($id);

        if (!$permission) {          
            throw new \RuntimeException(
                "No existe permiso con ID: {$id}. Favor de validar",
                Response::HTTP_NOT_FOUND
            );
        
        }
        return PermissionDTO::fromModel($permission);
    }
}