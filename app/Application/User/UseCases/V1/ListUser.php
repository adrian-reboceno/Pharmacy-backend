<?php

// app/Application/User/UseCases/V1/ListUser.php

namespace App\Application\User\UseCases\V1;

use App\Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * ──────────────────────────────────────────────────────────────
 * Use Case: ListUser
 * ──────────────────────────────────────────────────────────────
 *
 * @purpose
 * Retrieves a paginated and optionally filtered list of users from
 * the underlying persistence layer. This use case serves as the
 * *application layer* entry point for listing user data with
 * search, filtering, and sorting capabilities.
 *
 * By delegating the querying to the repository abstraction,
 * this class ensures that the application layer remains
 * decoupled from the infrastructure (ORM or database engine).
 *
 * ──────────────────────────────────────────────────────────────
 *
 * @layer Application
 *
 * @pattern Query Use Case (DDD)
 *
 * @version 1.0
 *
 * @author AFLR
 */
final class ListUser
{
    /**
     * Repository responsible for retrieving user data.
     */
    private readonly UserRepositoryInterface $repository;

    /**
     * Constructor.
     *
     * @param  UserRepositoryInterface  $repository
     *                                               Repository abstraction for user data retrieval and querying.
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Execute the user listing process.
     *
     * Retrieves a paginated list of users from the repository,
     * supporting optional filters for search and role,
     * as well as configurable sorting and pagination.
     *
     * @param  array  $filters
     *                          Optional filters to customize the query:
     *                          - `search`: (string) Search term for name or email.
     *                          - `role`: (string) Filter by a specific role name.
     *                          - `sort_by`: (string) Column name to sort by. Default: `id`.
     *                          - `sort_order`: ('asc'|'desc') Sorting direction. Default: `asc`.
     *                          - `page`: (int) Current page number.
     *                          - `per_page`: (int) Number of items per page. Default: 15.
     * @return LengthAwarePaginator
     *                              A Laravel paginator instance containing users and pagination metadata.
     *
     * ──────────────────────────────────────────────
     * Example usage:
     * ──────────────────────────────────────────────
     * ```php
     * $useCase = new ListUser($userRepository);
     *
     * $filters = [
     *     'search' => 'john',
     *     'role' => 'admin',
     *     'sort_by' => 'email',
     *     'sort_order' => 'desc',
     *     'per_page' => 20
     * ];
     *
     * $paginatedUsers = $useCase->handle($filters);
     *
     * foreach ($paginatedUsers as $user) {
     *     echo $user->name;
     * }
     * ```
     *
     * ──────────────────────────────────────────────
     * This approach provides:
     *  - Flexible and dynamic filtering for UI-driven queries.
     *  - Clean separation between application and infrastructure layers.
     *  - Extensibility for future business rules (e.g., status filtering, date ranges).
     * ──────────────────────────────────────────────
     */
    public function handle(array $filters = []): LengthAwarePaginator
    {
        // Initialize base query with eager-loaded roles.
        $query = $this->repository->query()->with('roles');

        // Filter by search term (name or email).
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"));
        }

        // Filter by specific role.
        if (! empty($filters['role'])) {
            $role = $filters['role'];
            $query->whereHas('roles', fn ($q) => $q->where('name', $role));
        }

        // Sorting configuration.
        $sortBy = $filters['sort_by'] ?? 'id';
        $sortOrder = $filters['sort_order'] ?? 'asc';
        $query->orderBy($sortBy, $sortOrder);

        // Pagination setup.
        $perPage = $filters['per_page'] ?? 15;

        return $query->paginate($perPage);
    }
}
