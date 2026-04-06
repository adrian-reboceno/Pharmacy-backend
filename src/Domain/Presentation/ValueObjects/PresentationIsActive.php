<?php
# src/Domain/Presentation/ValueObjects/PresentationIsActive.php

namespace App\Domain\Presentation\ValueObjects;

final class PresentationIsActive
{
     public function __construct(private bool $value)
    {
    }

    public function value(): bool
    {
        return $this->value;
    }
}