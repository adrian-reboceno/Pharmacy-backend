<?php
# app/Presentation/DTOs/V1/UnitOfMeasure/UnitOfMeasureResponseDTO.php

namespace App\Presentation\DTOs\V1\UnitOfMeasure;

use App\Domain\UnitOfMeasure\Entities\UnitOfMeasure as DomainUnitOfMeasure;

final class UnitOfMeasureResponseDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $symbol,
        public bool $is_active,
    ) {}

    public static function fromEntity(DomainUnitOfMeasure $unitOfMeasure): self
    {
        return new self(
            id: $unitOfMeasure->id()?->value() ?? 0,
            name: $unitOfMeasure->name()->value(),
            symbol: $unitOfMeasure->symbol()->value(),
            is_active: $unitOfMeasure->isActive()->value(),
        );
    }

    /** @return array{id:int,name:string,symbol:string,is_active:bool} */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'symbol' => $this->symbol,
            'is_active' => $this->is_active,
        ];
    }
}