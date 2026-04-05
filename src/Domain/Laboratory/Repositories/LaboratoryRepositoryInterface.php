<?php
# src/Domain/Laboratory/Repositories/LaboratoryRepositoryInterface.php

namespace App\Domain\Laboratory\Repositories;

use App\Domain\Laboratory\Entities\Laboratory;
use App\Domain\Laboratory\ValueObjects\LaboratoryId;

interface LaboratoryRepositoryInterface
{
    public function findById(LaboratoryId $id): ?Laboratory;

    public function findByName(string $name): ?Laboratory;

    /**
     * @return Laboratory[]
     */
    public function paginate(int $page, int $perPage, ?string $name = null): array;

    public function count(?string $name = null): int;

    public function save(Laboratory $laboratory): Laboratory;

    public function delete(LaboratoryId $id): void;
}