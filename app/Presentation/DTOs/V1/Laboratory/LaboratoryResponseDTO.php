<?php   

namespace App\Presentation\DTOs\V1\Laboratory;

use App\Domain\Laboratory\Entities\Laboratory as DomainLaboratory;

final class LaboratoryResponseDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $country,
        public bool $is_active,
    ) {}

    public static function fromEntity(DomainLaboratory $laboratory): self
    {
        return new self(
            id: $laboratory->id()?->value() ?? 0,
            name: $laboratory->name()->value(),
            country: $laboratory->country()?->value(),
            is_active: $laboratory->isActive()->value(),
        );
    }

    /** @return array{id:int,name:string,country:?string,is_active:bool} */
    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'country' => $this->country,
            'is_active'   => $this->is_active,
        ];
    }
}
