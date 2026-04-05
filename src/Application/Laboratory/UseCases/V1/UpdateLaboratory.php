<?php
# src/Application/Laboratory/UseCases/V1/UpdateLaboratory.php

namespace App\Application\Laboratory\UseCases\V1;

use App\Application\Laboratory\DTOs\V1\UpdateLaboratoryDTO;
use App\Domain\Laboratory\Entities\Laboratory;
use App\Domain\Laboratory\ValueObjects\LaboratoryId;
use App\Domain\Laboratory\ValueObjects\LaboratoryName;
use App\Domain\Laboratory\ValueObjects\LaboratoryCountry;
use App\Domain\Laboratory\ValueObjects\LaboratoryIsActive;
use App\Domain\Laboratory\Repositories\LaboratoryRepositoryInterface;
use App\Shared\Domain\Exceptions\NotFoundException;

final class UpdateLaboratory
{
    public function __construct(
        private readonly LaboratoryRepositoryInterface $laboratories
    ) {}

    public function execute(UpdateLaboratoryDTO $dto): Laboratory
    {
        $laboratory = $this->laboratories->findById(new LaboratoryId($dto->id));
        if ($laboratory === null) {
            throw new NotFoundException("Laboratory with ID {$id} not found.");
        }

        if ($dto->name !== null) {
            $laboratory->rename(new LaboratoryName($dto->name));
        }

        if ($dto->country !== null) {
            $laboratory->changeCountry(new LaboratoryCountry($dto->country));
        }

        if ($dto->isActive !== null) {
            $laboratory->isActive()->value()
                ? $laboratory->deactivate()
                : $laboratory->activate();

            // o si prefieres:
            // $categoryIsActive = new CategoryIsActive($dto->isActive);
            // if ($categoryIsActive->value()) { $category->activate(); } else { $category->deactivate(); }
        }

        return $this->laboratories->save($laboratory);
    }
}

