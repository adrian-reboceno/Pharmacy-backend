<?php
# src/Application/PharmaceuticalForm/UseCases/V1/CreatePharmaceuticalForm.php

namespace App\Application\PharmaceuticalForm\UseCases\V1;

use App\Application\PharmaceuticalForm\DTOs\V1\CreatePharmaceuticalFormDTO;
use App\Domain\PharmaceuticalForm\Entities\PharmaceuticalForm;
use App\Domain\PharmaceuticalForm\Repositories\PharmaceuticalFormRepositoryInterface;
use App\Domain\PharmaceuticalForm\ValueObjects\PharmaceuticalFormName;
use App\Domain\PharmaceuticalForm\ValueObjects\PharmaceuticalFormIsActive;
use App\Shared\Domain\Exceptions\AlreadyExistsException;

final class CreatePharmaceuticalForm
{
    public function __construct(
        private readonly PharmaceuticalFormRepositoryInterface $pharmaceuticalForms
    ) {}

    public function execute(CreatePharmaceuticalFormDTO $dto): PharmaceuticalForm
    {
        $existing = $this->pharmaceuticalForms->findByName($dto->name);
        if ($existing !== null) {
            throw new AlreadyExistsException('A pharmaceutical form with this name already exists.');
        }

        $pharmaceuticalForm = new PharmaceuticalForm(
            id: null,
            name: new PharmaceuticalFormName($dto->name),
            isActive: new PharmaceuticalFormIsActive($dto->isActive),
        );

        return $this->pharmaceuticalForms->save($pharmaceuticalForm);
    }
}