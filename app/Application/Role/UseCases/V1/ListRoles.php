<?php
#app/Application/Role/UseCases/V1/ListRoles.php;
namespace App\Application\Role\UseCases\V1;


use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Presentation\DTOs\V1\RoleDTO;
use Illuminate\Pagination\LengthAwarePaginator;

class ListRoles
{
    public function __construct(protected RoleRepositoryInterface $repo) {}

    public function handle(int $perPage = 10): LengthAwarePaginator
    {
        $paginator = $this->repo->query()
                                ->orderBy('id', 'asc')
                                ->paginate($perPage);

        // Transformar cada modelo a DTO
        $paginator->getCollection()->transform(fn($role) => RoleDTO::fromModel($role));

        return $paginator;
    }
}