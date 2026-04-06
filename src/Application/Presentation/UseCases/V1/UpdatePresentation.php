<?php
# src/Application/Presentation/UseCases/V1/UpdatePresentation.php

namespace App\Application\Presentation\UseCases\V1;

use App\Application\Presentation\DTOs\V1\UpdatePresentationDTO;
use App\Domain\Presentation\Entities\Presentation;
use App\Domain\Presentation\Repositories\PresentationRepositoryInterface;
use App\Domain\Presentation\ValueObjects\PresentationId;
use App\Domain\Presentation\ValueObjects\PresentationName;
use App\Domain\Presentation\ValueObjects\PresentationIsActive;
use App\Shared\Domain\Exceptions\NotFoundException;

final class UpdatePresentation
{
    public function __construct(
        private readonly PresentationRepositoryInterface $presentations
    ) {}

    public function execute(UpdatePresentationDTO $dto): Presentation
    {
        $presentationId = new PresentationId($dto->id);
        $presentation   = $this->presentations->findById($presentationId);

        if ($presentation === null) {
            throw new NotFoundException("Presentation with ID {$dto->id} not found.");
        }

        if ($dto->name !== null) {
            $presentation->rename(new PresentationName($dto->name));
        }

        if ($dto->isActive !== null) {
            $presentation->isActive()->value()
                ? $presentation->deactivate()
                : $presentation->activate();
        }

        return $this->presentations->save($presentation);
    }
}
