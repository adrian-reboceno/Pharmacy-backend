<?php
# src/Domain/UnitOfMeasure/Entities/UnitOfMeasure.php

namespace Domain\UnitOfMeasure\Entities;

use Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureId;
use Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureName;
use Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureSymbol;
use Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureIsActive;

final class UnitOfMeasure
{
    public function __construct(
        private ?UnitOfMeasureId $id,
        private UnitOfMeasureName $name,
        private UnitOfMeasureSymbol $symbol,
        private UnitOfMeasureIsActive $isActive,
    ) {}

    public function id(): ?UnitOfMeasureId
    {
        return $this->id;
    }

    public function name(): UnitOfMeasureName
    {
        return $this->name;
    }

    public function symbol(): UnitOfMeasureSymbol
    {
        return $this->symbol;
    }

    public function isActive(): UnitOfMeasureIsActive
    {
        return $this->isActive;
    }

    public function rename(UnitOfMeasureName $name): void
    {
        $this->name = $name;
    }

    public function changeSymbol(UnitOfMeasureSymbol $symbol): void
    {
        $this->symbol = $symbol;
    }

    public function activate(): void
    {
        $this->isActive = new UnitOfMeasureIsActive(true);
    }

    public function deactivate(): void
    {
        $this->isActive = new UnitOfMeasureIsActive(false);
    }
}

