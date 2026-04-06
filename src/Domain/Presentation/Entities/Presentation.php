<?php
# src/Domain/Presentation/Entities/Presentartion.php

namespace App\Domain\Presentation\Entities;

use App\Domain\Presentation\ValueObjects\PresentationId;
use App\Domain\Presentation\ValueObjects\PresentationName;
use App\Domain\Presentation\ValueObjects\PresentationIsActive;

final class Presentation
{
    public function __construct(
        private ?PresentationId $id,
        private PresentationName $name,
        private PresentationIsActive $isActive,
    ) {}

    public function id(): ?PresentationId
    {
        return $this->id;
    }

    public function name(): PresentationName
    {
        return $this->name;
    }

    public function isActive(): PresentationIsActive
    {
        return $this->isActive;
    }

    public function rename(PresentationName $name): void
    {
        $this->name = $name;
    }

    public function activate(): void
    {
        $this->isActive = new PresentationIsActive(true);
    }

    public function deactivate(): void
    {
        $this->isActive = new PresentationIsActive(false);
    }
}