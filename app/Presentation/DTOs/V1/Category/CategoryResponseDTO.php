<?php
# app/Presentation/DTOs/V1/Category/CategoryResponseDTO.php

namespace App\Presentation\DTOs\V1\Category;

use App\Domain\Category\Entities\Category as DomainCategory;

final class CategoryResponseDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public bool $is_active,
    ) {}

    public static function fromEntity(DomainCategory $category): self
    {
        return new self(
            id: $category->id()?->value() ?? 0,
            name: $category->name()->value(),
            description: $category->description()?->value(),
            is_active: $category->isActive()->value(),
        );
    }

    /** @return array{id:int,name:string,description:?string,is_active:bool} */
    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'is_active'   => $this->is_active,
        ];
    }
}
