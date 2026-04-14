<?php
# src/Domain/UnitOfMeasure/Repositories/UnitOfMeasureRepositoryInterface.php

namespace App\Domain\UnitOfMeasure\Repositories;

use App\Domain\UnitOfMeasure\Entities\UnitOfMeasure;
use App\Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureId;
use App\Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureName;
use App\Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureSymbol;

interface UnitOfMeasureRepositoryInterface
{
    public function findById(UnitOfMeasureId $id): ?UnitOfMeasure;

    public function findByName(string $name): ?UnitOfMeasure;

    public function findBySymbol(string $symbol): ?UnitOfMeasure;

    /**
     * @return UnitOfMeasure[]
     */
    public function paginate(int $page, int $perPage, ?string $name = null, ?string $symbol = null, ?bool $isActive = null): array;

    public function count(?string $name = null, ?string $symbol = null, ?bool $isActive = null): int;

    public function save(UnitOfMeasure $unitOfMeasure): UnitOfMeasure;

    public function delete(UnitOfMeasureId $id): void;
}
