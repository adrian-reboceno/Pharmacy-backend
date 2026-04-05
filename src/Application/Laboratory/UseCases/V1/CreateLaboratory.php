<?php
# src/Application/Laboratory/UseCases/V1/CreateLaboratory.php

namespace App\Application\Laboratory\UseCases\V1;

use App\Application\Laboratory\DTOs\V1\CreateLaboratoryDTO;
use App\Domain\Laboratory\Entities\Laboratory;
use App\Domain\Laboratory\ValueObjects\LaboratoryId;
use App\Domain\Laboratory\ValueObjects\LaboratoryName;
use App\Domain\Laboratory\ValueObjects\LaboratoryCountry;
use App\Domain\Laboratory\ValueObjects\LaboratoryIsActive;
use App\Domain\Laboratory\Repositories\LaboratoryRepositoryInterface;
use App\Shared\Domain\Exceptions\AlreadyExistsException;

final class CreateLaboratory
{
    public function __construct(
        private LaboratoryRepositoryInterface $repository
    ) {}

    public function execute(CreateLaboratoryDTO $dto): Laboratory
    {
        $existing = $this->repository->findByName($dto->name);
        if ($existing !== null) {
            throw new AlreadyExistsException('A laboratory with this name already exists.');
        }
        $laboratory = new Laboratory(
            id: null,
            name: new LaboratoryName($dto->name),
            country: new LaboratoryCountry($dto->country),
            isActive: new LaboratoryIsActive($dto->isActive),
        );

        return $this->repository->save($laboratory);
    }
}
