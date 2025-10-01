<?php
#App/Domain/Permission/Repositories/PermissionRepositoryInterface.php;
namespace App\Domain\Permission\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

interface PermissionRepositoryInterface
{
    /**
     * Devuelve un query builder para permisos
     *
     * Esto permite aplicar filtros, ordenamiento y paginación
     */
    public function query(): Builder;

    /**
     * Busca un permiso por ID
     *
     * @param int $id
     * @return \App\Domain\Permission\Permission|null
     */
    public function find(int $id): ?object;

    /**
     * Crea un nuevo permiso
     *
     * @param array $data
     * @return \App\Domain\Permission\Permission
     */
    public function create(array $data): object;

    /**
     * Actualiza un permiso existente
     *
     * @param int $id
     * @param array $data
     * @return \App\Domain\Permission\Permission
     */
    public function update(int $id, array $data): object;

    /**
     * Elimina un permiso
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
