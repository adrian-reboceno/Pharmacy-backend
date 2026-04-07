<?php
# src/Application/PharmaceuticalForm/UseCases/V1/DeletePharmaceuticalForm.php

namespace App\Application\PharmaceuticalForm\UseCases\V1;

use App\Domain\PharmaceuticalForm\Repositories\PharmaceuticalFormRepositoryInterface;
use App\Domain\PharmaceuticalForm\ValueObjects\PharmaceuticalFormId;
use App\Shared\Domain\Exceptions\NotFoundException;

final class DeletePharmaceuticalForm
{
    public function __construct(
        private readonly PharmaceuticalFormRepositoryInterface $pharmaceuticalForms
    ) {}

    public function execute(int $id): void
    {
        $id = new PharmaceuticalFormId($id);
        $pharmaceuticalForm = $this->pharmaceuticalForms->findById($id);

        if ($pharmaceuticalForm === null) {
            throw new NotFoundException("Pharmaceutical form with ID {$id} not found.");
        }

        $pharmaceuticalForm->deactivate();
        $this->pharmaceuticalForms->save($pharmaceuticalForm);
    }
}