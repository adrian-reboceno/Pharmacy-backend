<?php
# app/Presentation/DTOs/V1/Presentation/PresentationResponseDTO.php

namespace App\Presentation\DTOs\V1\Presentation;

use App\Domain\Presentation\Entities\Presentation as DomainPresentation;

final class PresentationResponseDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public bool $is_active,
    ) {}

    public static function fromEntity(DomainPresentation $presentation): self
    {
        return new self(
            id: $presentation->id()?->value() ?? 0,
            name: $presentation->name()->value(),
            is_active: $presentation->isActive()->value(),
        );
    }

    /** @return array{id:int,name:string,is_active:bool} */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_active' => $this->is_active,
        ];
    }
}