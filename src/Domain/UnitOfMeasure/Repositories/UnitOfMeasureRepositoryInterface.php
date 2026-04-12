<?php
# src/Domain/UnitOfMeasure/Repositories/UnitOfMeasureRepositoryInterface.php

namespace Domain\UnitOfMeasure\Repositories;

use Domain\UnitOfMeasure\Entities\UnitOfMeasure;
use Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureId;

interface UnitOfMeasureRepositoryInterface
{
    public function findById(UnitOfMeasureId $id): ?UnitOfMeasure;

    public function findByName(string $name): ?UnitOfMeasure;

    /**
     * @return UnitOfMeasure[]
     */
    public function paginate(int $page, int $perPage, ?string $name = null): array;

    public function count(?string $name = null): int;

    public function save(UnitOfMeasure $unitOfMeasure): UnitOfMeasure;

    public function delete(UnitOfMeasureId $id): void;
}
