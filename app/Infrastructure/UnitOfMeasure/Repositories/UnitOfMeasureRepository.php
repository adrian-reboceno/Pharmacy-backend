<?php
# app/Infrastructure/UnitOfMeasure/Repositories/UnitOfMeasureRepository.php

namespace App\Infrastructure\UnitOfMeasure\Repositories;

use App\Domain\UnitOfMeasure\Entities\UnitOfMeasure as DomainUnitOfMeasure;
use App\Domain\UnitOfMeasure\Repositories\UnitOfMeasureRepositoryInterface;
use App\Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureId;
use App\Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureName;
use App\Domain\UnitOfMeasure\ValueObjects\UnitOfMeasureSymbol;
use App\Infrastructure\UnitOfMeasure\Mappers\UnitOfMeasureMapper;
use App\Infrastructure\UnitOfMeasure\Models\UnitOfMeasure as UnitOfMeasure;
use Illuminate\Support\Facades\DB;

final class UnitOfMeasureRepository implements UnitOfMeasureRepositoryInterface
{
    public function findById(UnitOfMeasureId $id): ?DomainUnitOfMeasure
    {
        $model = UnitOfMeasure::find($id->value());

        return $model ? UnitOfMeasureMapper::toDomain($model) : null;
    }

    public function findByName(string $name): ?DomainUnitOfMeasure
    {
        $model = UnitOfMeasure::where('name', $name)->first();

        return $model ? UnitOfMeasureMapper::toDomain($model) : null;
    }

    public function findBySymbol(string $symbol): ?DomainUnitOfMeasure
    {
        $model = UnitOfMeasure::where('symbol', $symbol)->first();

        return $model ? UnitOfMeasureMapper::toDomain($model) : null;
    }

    /** @return DomainUnitOfMeasure[] */
    public function paginate(int $page, int $perPage, ?string $name = null, ?string $symbol = null, ?bool $isActive = null): array
    {
        $query = UnitOfMeasure::query();

        if ($name !== null && $name !== '') {
            $query->where('name', 'LIKE', "%{$name}%");
        }

        if ($symbol !== null && $symbol !== '') {
            $query->where('symbol', 'LIKE', "%{$symbol}%");
        }

        if ($isActive !== null) {
            $query->where('is_active', $isActive);
        }

        $models = $query
            ->orderBy('id')
            ->forPage($page, $perPage)
            ->get();

        return $models
            ->map(fn (UnitOfMeasure $model) => UnitOfMeasureMapper::toDomain($model))
            ->all();
    }

    public function count(?string $name = null, ?string $symbol = null, ?bool $isActive = null): int
    {
        $query = UnitOfMeasure::query();

        if ($name !== null && $name !== '') {
            $query->where('name', 'LIKE', "%{$name}%");
        }

        if ($symbol !== null && $symbol !== '') {
            $query->where('symbol', 'LIKE', "%{$symbol}%");
        }

        if ($isActive !== null) {
            $query->where('is_active', $isActive);
        }

        return $query->count();
    }

    public function save(DomainUnitOfMeasure $unitOfMeasure): DomainUnitOfMeasure
    {
        return DB::transaction(function () use ($unitOfMeasure): DomainUnitOfMeasure {
            if ($unitOfMeasure->id() === null) {
                $model = UnitOfMeasure::create([
                    'name'        => $unitOfMeasure->name()->value(),
                    'symbol'      => $unitOfMeasure->symbol()->value(),
                    'is_active'   => $unitOfMeasure->isActive()->value(),
                ]);
            } else {
                $model = UnitOfMeasure::findOrFail($unitOfMeasure->id()->value());
                $model->update([
                    'name'        => $unitOfMeasure->name()->value(),
                    'symbol'      => $unitOfMeasure->symbol()->value(),
                    'is_active'   => $unitOfMeasure->isActive()->value(),
                ]);
            }

            return UnitOfMeasureMapper::toDomain($model->fresh());
        });
    }

    public function delete(UnitOfMeasureId $id): void
    {
        UnitOfMeasure::where('id', $id->value())->delete();
    }
}
