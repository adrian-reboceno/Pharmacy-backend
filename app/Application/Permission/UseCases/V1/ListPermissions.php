<?php
#app/Application/Permission/UseCases/V1/ListPermissions.php;
namespace App\Application\Permission\UseCases\V1;


use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Presentation\DTOs\V1\PermissionDTO;
use Illuminate\Pagination\LengthAwarePaginator;

class ListPermissions
{
    public function __construct(protected PermissionRepositoryInterface $repo) {}

    public function handle(int $perPage = 10): LengthAwarePaginator
    {
        $paginator = $this->repo->query()
                                ->orderBy('id', 'asc')
                                ->paginate($perPage);

        // Transformar cada modelo a DTO
        $paginator->getCollection()->transform(fn($permission) => PermissionDTO::fromModel($permission));

        return $paginator;
    }
}