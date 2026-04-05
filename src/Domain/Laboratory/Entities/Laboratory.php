<?php
# src/Domain/Laboratory/Entities/Laboratory.php

namespace App\Domain\Laboratory\Entities;

use App\Domain\Laboratory\ValueObjects\LaboratoryId;
use App\Domain\Laboratory\ValueObjects\LaboratoryName;
use App\Domain\Laboratory\ValueObjects\LaboratoryCountry;
use App\Domain\Laboratory\ValueObjects\LaboratoryIsActive;

final class Laboratory
{
    public function __construct(
        private ?LaboratoryId $id,
        private LaboratoryName $name,
        private LaboratoryCountry $country,
        private LaboratoryIsActive $isActive = new LaboratoryIsActive(true),
    ) {}

     public function id(): ?LaboratoryId
    {
        return $this->id;
    }

    public function name(): LaboratoryName
    {
        return $this->name;
    }

    public function country(): LaboratoryCountry
    {
        return $this->country;
    }

    public function isActive(): LaboratoryIsActive
    {
        return $this->isActive;
    }

    public function rename(LaboratoryName $name): void
    {
        $this->name = $name;
    }

    public function changeCountry(?LaboratoryCountry $country): void
    {
        $this->country = $country;
    }

    public function activate(): void
    {
        $this->isActive = new LaboratoryIsActive(true);
    }

    public function deactivate(): void
    {
        $this->isActive = new LaboratoryIsActive(false);
    }
    

    
}
