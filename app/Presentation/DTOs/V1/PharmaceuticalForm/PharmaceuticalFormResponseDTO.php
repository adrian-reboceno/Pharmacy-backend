<?php
# app/Presentation/DTOs/V1/PharmaceuticalForm/PharmaceuticalFormResponseDTO.php

namespace App\Presentation\DTOs\V1\PharmaceuticalForm;

use App\Domain\PharmaceuticalForm\Entities\PharmaceuticalForm as DomainPharmaceuticalForm;

final class PharmaceuticalFormResponseDTO
{
    public function __construct(
        public int $id,
        public string $name,
       
        public bool $is_active,
    ) {}

    public static function fromEntity(DomainPharmaceuticalForm $pharmaceuticalForm): self
    {
        return new self(
            id: $pharmaceuticalForm->id()?->value() ?? 0,
            name: $pharmaceuticalForm->name()->value(),
            is_active: $pharmaceuticalForm->isActive()->value(),
        );
    }

    /** @return array{id:int,name:string,is_active:bool} */
    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'is_active'   => $this->is_active,
        ];
    }
}