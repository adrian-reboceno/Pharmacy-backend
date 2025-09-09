<?php
#app/DTOs/V1/PermissionDTO.php
namespace App\DTOs\V1;

class PermissionDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $guard_name = 'api', // usando JWT guard
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            guard_name: $data['guard_name'] ?? 'api',
        );
    }
}
