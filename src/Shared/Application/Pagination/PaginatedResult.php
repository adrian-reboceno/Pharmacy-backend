<?php
# src/Shared/Application/Pagination/PaginatedResult.php

namespace App\Shared\Application\Pagination;

/**
 * Generic pagination result for Application layer.
 *
 * @template T
 */
final class PaginatedResult
{
    /**
     * @var array<int, mixed> Lista de items (por ejemplo, Domain Users)
     */
    private array $items;

    /**
     * @var int Total de registros en la fuente de datos.
     */
    private int $total;

    /**
     * @var int Página actual (1-based).
     */
    private int $page;

    /**
     * @var int Registros por página.
     */
    private int $perPage;

    /**
     * @param array<int, mixed> $items
     */
    public function __construct(array $items, int $total, int $page, int $perPage)
    {
        $this->items   = $items;
        $this->total   = $total;
        $this->page    = $page;
        $this->perPage = $perPage;
    }

    /**
     * @return array<int, mixed>
     */
    public function items(): array
    {
        return $this->items;
    }

    public function total(): int
    {
        return $this->total;
    }

    public function page(): int
    {
        return $this->page;
    }

    public function perPage(): int
    {
        return $this->perPage;
    }

    public function lastPage(): int
    {
        return (int) ceil($this->total / max($this->perPage, 1));
    }
}
