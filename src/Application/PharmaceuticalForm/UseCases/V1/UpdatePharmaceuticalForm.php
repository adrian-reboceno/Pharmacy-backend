<?php
# src/Application/PharmaceuticalForm/UseCases/V1/UpdatePharmaceuticalForm.php

namespace App\Application\PharmaceuticalForm\UseCases\V1;

use App\Application\PharmaceuticalForm\DTOs\V1\UpdatePharmaceuticalFormDTO;
use App\Domain\PharmaceuticalForm\Entities\PharmaceuticalForm;
use App\Domain\PharmaceuticalForm\Repositories\PharmaceuticalFormRepositoryInterface;
use App\Domain\PharmaceuticalForm\ValueObjects\PharmaceuticalFormId;
use App\Domain\PharmaceuticalForm\ValueObjects\PharmaceuticalFormName;
use App\Domain\PharmaceuticalForm\ValueObjects\PharmaceuticalFormIsActive;
use App\Shared\Domain\Exceptions\NotFoundException;

final class UpdatePharmaceuticalForm
{
    public function __construct(
        private readonly PharmaceuticalFormRepositoryInterface $presentations
    ) {}

    public function execute(UpdatePharmaceuticalFormDTO $dto): PharmaceuticalForm
    {
        $pharmaceuticalFormId = new PharmaceuticalFormId($dto->id);
        $pharmaceuticalForm   = $this->presentations->findById($pharmaceuticalFormId);

        if ($pharmaceuticalForm === null) {
            throw new NotFoundException("Pharmaceutical Form with ID {$dto->id} not found.");
        }

        if ($dto->name !== null) {
            $pharmaceuticalForm->rename(new PharmaceuticalFormName($dto->name));
        }

        if ($dto->isActive !== null) {
            $pharmaceuticalForm->isActive()->value()
                ? $pharmaceuticalForm->deactivate()
                : $pharmaceuticalForm->activate();
        }

        return $this->presentations->save($pharmaceuticalForm);
    }
}