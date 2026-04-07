<?php
# src/Domain/PharmaceuticalForm/Entities/PharmaceuticalForm.php

namespace App\Domain\PharmaceuticalForm\Entities;

use App\Domain\PharmaceuticalForm\ValueObjects\PharmaceuticalFormId;
use App\Domain\PharmaceuticalForm\ValueObjects\PharmaceuticalFormName;
use App\Domain\PharmaceuticalForm\ValueObjects\PharmaceuticalFormIsActive;

final class PharmaceuticalForm
{
    public function __construct(
        private ?PharmaceuticalFormId $id,
        private PharmaceuticalFormName $name,
        private PharmaceuticalFormIsActive $isActive,
    ) {}

    public function id(): ?PharmaceuticalFormId
    {
        return $this->id;
    }

    public function name(): PharmaceuticalFormName
    {
        return $this->name;
    }

    public function isActive(): PharmaceuticalFormIsActive
    {
        return $this->isActive;
    }

    public function rename(PharmaceuticalFormName $name): void
    {
        $this->name = $name;
    }

    public function activate(): void
    {
        $this->isActive = new PharmaceuticalFormIsActive(true);
    }

    public function deactivate(): void
    {
        $this->isActive = new PharmaceuticalFormIsActive(false);
    }
}