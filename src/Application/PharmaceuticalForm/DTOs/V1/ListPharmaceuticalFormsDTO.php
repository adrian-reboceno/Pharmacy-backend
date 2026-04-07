<?php
# src/Application/PharmaceuticalForm/DTOs/V1/ListPharmaceuticalFormsDTO.php

namespace App\Application\PharmaceuticalForm\DTOs\V1;

final class ListPharmaceuticalFormsDTO
{
    public function __construct(
        public readonly int $page = 1,
        public readonly int $perPage = 15,
        public readonly ?string $name = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            page: (int)($data['page'] ?? 1),
            perPage: (int)($data['per_page'] ?? 15),
            name: $data['name'] ?? null,
        );
    }
}