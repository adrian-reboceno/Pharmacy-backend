<?php
#app/Application/Permission/UseCases/V1/DeletePermission.php;
namespace App\Application\Permission\UseCases\V1;

use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use Illuminate\Http\Response;

class DeletePermission
{
    public function __construct(protected PermissionRepositoryInterface $repo) {}

    public function handle(int $id): bool
    {
        $permission = $this->repo->find($id);

        if (!$permission) {          
            throw new \RuntimeException(
                "No existe permiso con ID: {$id} y no se puede eliminar. Favor de validar",
                Response::HTTP_NOT_FOUND
            );
        
        }

        return $this->repo->delete($id);
    }
}