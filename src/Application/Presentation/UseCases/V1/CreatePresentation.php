<?php
# src/Application/Presentation/UseCases/V1/CreatePresentation.php

namespace App\Application\Presentation\UseCases\V1;

use App\Application\Presentation\DTOs\V1\CreatePresentationDTO;
use App\Domain\Presentation\Entities\Presentation;
use App\Domain\Presentation\Repositories\PresentationRepositoryInterface;
use App\Domain\Presentation\ValueObjects\PresentationName;
use App\Domain\Presentation\ValueObjects\PresentationIsActive;
use App\Shared\Domain\Exceptions\AlreadyExistsException;

final class CreatePresentation
{
    public function __construct(
        private readonly PresentationRepositoryInterface $presentations
    ) {}

    public function execute(CreatePresentationDTO $dto): Presentation
    {
        $existing = $this->presentations->findByName($dto->name);
        if ($existing !== null) {
            throw new AlreadyExistsException('A presentation with this name already exists.');
        }

        $presentation = new Presentation(
            id: null,
            name: new PresentationName($dto->name),
            isActive: new PresentationIsActive($dto->isActive),
        );

        return $this->presentations->save($presentation);
    }
}