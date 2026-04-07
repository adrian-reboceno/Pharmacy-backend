<?php
# src/Application/PharmaceuticalForm/UseCases/V1/ShowPharmaceuticalForm.php

namespace App\Application\PharmaceuticalForm\UseCases\V1;

use App\Domain\PharmaceuticalForm\Entities\PharmaceuticalForm;
use App\Domain\PharmaceuticalForm\Repositories\PharmaceuticalFormRepositoryInterface;
use App\Domain\PharmaceuticalForm\ValueObjects\PharmaceuticalFormId;
use App\Shared\Domain\Exceptions\NotFoundException;

final class ShowPharmaceuticalForm
{
    public function __construct(
        private readonly PharmaceuticalFormRepositoryInterface $pharmaceuticalForms
    ) {}

    public function execute(int $id): PharmaceuticalForm
    {
        $pharmaceuticalFormId = new PharmaceuticalFormId($id);
        $pharmaceuticalForm   = $this->pharmaceuticalForms->findById($pharmaceuticalFormId);

        if ($pharmaceuticalForm === null) {
            throw new NotFoundException("Pharmaceutical Form with ID {$id} not found.");
        }

        return $pharmaceuticalForm;
    }
}