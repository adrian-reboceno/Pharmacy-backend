<?php
#app/DTOs/V1/DTO.php
namespace App\DTOs\V1\Rol;

class RolDTO
{
     public function __construct(
        public string $name,
        public string $guard_name
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            guard_name: $data['guard_name'] ?? 'api'
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'guard_name' => $this->guard_name,
        ];
    }
}
