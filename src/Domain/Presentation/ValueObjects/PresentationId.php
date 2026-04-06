<?php
# src/Domain/Presentation/ValueObjects/PresentationId.php

namespace App\Domain\Presentation\ValueObjects;

final class PresentationId
{
    public function __construct(private int $value)
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException('PresentationId must be positive.');
        }
    }

    public function value(): int
    {
        return $this->value;
    }
}
