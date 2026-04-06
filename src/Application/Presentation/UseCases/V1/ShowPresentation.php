<?php
# src/Application/Presentation/UseCases/V1/ShowPresentation.php

namespace App\Application\Presentation\UseCases\V1;

use App\Domain\Presentation\Entities\Presentation;
use App\Domain\Presentation\Repositories\PresentationRepositoryInterface;
use App\Domain\Presentation\ValueObjects\PresentationId;
use App\Shared\Domain\Exceptions\NotFoundException;

final class ShowPresentation
{
    public function __construct(
        private readonly PresentationRepositoryInterface $presentations
    ) {}

    public function execute(int $id): Presentation
    {
        $presentationId = new PresentationId($id);
        $presentation   = $this->presentations->findById($presentationId);

        if ($presentation === null) {
            throw new NotFoundException("Presentation with ID {$id} not found.");
        }

        return $presentation;
    }
}