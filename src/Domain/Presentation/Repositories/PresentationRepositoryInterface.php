<?php
# src/Domain/Presentation/Repositories/PresentationRepositoryInterface.php

namespace App\Domain\Presentation\Repositories;

use App\Domain\Presentation\Entities\Presentation;
use App\Domain\Presentation\ValueObjects\PresentationId;

interface PresentationRepositoryInterface
{
    public function findById(PresentationId $id): ?Presentation;

    public function findByName(string $name): ?Presentation;

    /**
     * @return Presentation[]
     */
    public function paginate(int $page, int $perPage, ?string $name = null): array;

    public function count(?string $name = null): int;

    public function save(Presentation $presentation): Presentation;

    public function delete(PresentationId $id): void;
}