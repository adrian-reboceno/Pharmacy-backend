<?php
# src/Application/Laboratory/UseCases/V1/ShowLaboratory.php

namespace App\Application\Laboratory\UseCases\V1;

use App\Domain\Laboratory\Entities\Laboratory;
use App\Domain\Laboratory\ValueObjects\LaboratoryId;
use App\Domain\Laboratory\Repositories\LaboratoryRepositoryInterface;
use App\Shared\Domain\Exceptions\NotFoundException;

final class ShowLaboratory
{
    public function __construct(
        private readonly LaboratoryRepositoryInterface $laboratories
    ) {}

    public function execute(int $id): Laboratory
    {
       $laboratory = new LaboratoryId($id);
       $laboratory = $this->laboratories->findById($laboratory);
       if ($laboratory === null) {
           throw new NotFoundException("Laboratory with ID {$id} not found.");
       }
       return $laboratory;
    }
}
