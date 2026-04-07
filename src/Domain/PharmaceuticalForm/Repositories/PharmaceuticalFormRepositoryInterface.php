<?php
# src/Domain/PharmaceuticalForm/Repositories/PharmaceuticalFormRepositoryInterface.php

namespace App\Domain\PharmaceuticalForm\Repositories;

use App\Domain\PharmaceuticalForm\Entities\PharmaceuticalForm;
use App\Domain\PharmaceuticalForm\ValueObjects\PharmaceuticalFormId;

interface PharmaceuticalFormRepositoryInterface
{
    public function findById(PharmaceuticalFormId $id): ?PharmaceuticalForm;

    public function findByName(string $name): ?PharmaceuticalForm;

    /**
     * @return PharmaceuticalForm[]
     */
    public function paginate(int $page, int $perPage, ?string $name = null): array;

    public function count(?string $name = null): int;

    public function save(PharmaceuticalForm $pharmaceuticalForm): PharmaceuticalForm;

    public function delete(PharmaceuticalFormId $id): void;
}