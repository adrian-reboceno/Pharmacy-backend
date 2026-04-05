<?php
# src/Application/Laboratory/UseCases/V1/DeleteLaboratory.php

namespace App\Application\Laboratory\UseCases\V1;

use App\Domain\Laboratory\Repositories\LaboratoryRepositoryInterface;
use App\Domain\Laboratory\ValueObjects\LaboratoryId;
use App\Shared\Domain\Exceptions\NotFoundException;

final class DeleteLaboratory
{
    public function __construct(
        private readonly LaboratoryRepositoryInterface $laboratories
    ) {}

    public function execute(int $id): void
    {
        $laboratory = $this->laboratories->findById(new LaboratoryId($id));
        if ($laboratory === null) {
            throw new NotFoundException('Laboratory not found.');
        }
        $laboratory->deactivate();

        $this->laboratories->save($laboratory);
    }
}
