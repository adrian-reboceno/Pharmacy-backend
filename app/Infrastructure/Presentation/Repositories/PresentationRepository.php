<?php
# app/Infrastructure/Presentation/Repositories/PresentationRepository.php

namespace App\Infrastructure\Presentation\Repositories;

use App\Domain\Presentation\Entities\Presentation as DomainPresentation;
use App\Domain\Presentation\Repositories\PresentationRepositoryInterface;
use App\Domain\Presentation\ValueObjects\PresentationId;
use App\Infrastructure\Presentation\Mappers\PresentationMapper;
use App\Infrastructure\Presentation\Models\Presentation as Presentation;
use Illuminate\Support\Facades\DB;

final class PresentationRepository implements PresentationRepositoryInterface
{
    public function findById(PresentationId $id): ?DomainPresentation
    {
        $model = Presentation::find($id->value());

        return $model ? PresentationMapper::toDomain($model) : null;
    }

    public function findByName(string $name): ?DomainPresentation
    {
        $model = Presentation::where('name', $name)->first();

        return $model ? PresentationMapper::toDomain($model) : null;
    }

    /** @return DomainPresentation[] */
    public function paginate(int $page, int $perPage, ?string $name = null): array
    {
        $query = Presentation::query();

        if ($name !== null && $name !== '') {
            $query->where('name', 'LIKE', "%{$name}%");
        }

        $models = $query
            ->orderBy('id')
            ->forPage($page, $perPage)
            ->get();

        return $models
            ->map(fn (Presentation $model) => PresentationMapper::toDomain($model))
            ->all();
    }

    public function count(?string $name = null): int
    {
        $query = Presentation::query();

        if ($name !== null && $name !== '') {
            $query->where('name', 'LIKE', "%{$name}%");
        }

        return $query->count();
    }

    public function save(DomainPresentation $presentation): DomainPresentation
    {
        return DB::transaction(function () use ($presentation): DomainPresentation {
            if ($presentation->id() === null) {
                $model = Presentation::create([
                    'name'        => $presentation->name()->value(),
                    'is_active'   => $presentation->isActive()->value(),
                ]);
            } else {
                $model = Presentation::findOrFail($presentation->id()->value());
                $model->update([
                    'name'        => $presentation->name()->value(),
                    'is_active'   => $presentation->isActive()->value(),
                ]);
            }

            return PresentationMapper::toDomain($model->fresh());
        });
    }

    public function delete(PresentationId $id): void
    {
        Presentation::where('id', $id->value())->delete();
    }
}
