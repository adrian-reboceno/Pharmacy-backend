<?php
# src/Domain/Presentation/ValueObjects/PresentationName.php

namespace App\Domain\Presentation\ValueObjects;

final class PresentationName
{
    public function __construct(private string $value)
    {
        $value = trim($value);
        if ($value === '') {
            throw new \InvalidArgumentException('PresentationName cannot be empty.');
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}