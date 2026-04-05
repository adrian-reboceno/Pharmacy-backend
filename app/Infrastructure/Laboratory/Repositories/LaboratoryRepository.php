<?php

# app/Infrastructure/Laboratory/Repositories/LaboratoryRepository.php

namespace App\Infrastructure\Laboratory\Repositories;

use App\Domain\Laboratory\Entities\Laboratory as DomainLaboratory;
use App\Domain\Laboratory\Repositories\LaboratoryRepositoryInterface;
use App\Domain\Laboratory\ValueObjects\LaboratoryId;
use App\Infrastructure\Laboratory\Mappers\LaboratoryMapper;
use App\Infrastructure\Laboratory\Models\Laboratory;
use Illuminate\Support\Facades\DB;

final class LaboratoryRepository implements LaboratoryRepositoryInterface
{
    public function findById(LaboratoryId $id): ?DomainLaboratory
    {
        $model = Laboratory::find($id->value());

        return $model ? LaboratoryMapper::toDomain($model) : null;
    }

    public function findByName(string $name): ?DomainLaboratory
    {
         $model = Laboratory::where('name', $name)->first();

        return $model ? LaboratoryMapper::toDomain($model) : null;
    }

    /** @return DomainLaboratory[] */
    public function paginate(int $page, int $perPage, ?string $name = null): array
    {
         $query = Laboratory::query();

        if ($name !== null && $name !== '') {
            $query->where('name', 'LIKE', "%{$name}%");
        }

        $models = $query
            ->orderBy('id')
            ->forPage($page, $perPage)
            ->get();

        return $models
            ->map(fn (Laboratory $model) => LaboratoryMapper::toDomain($model))
            ->all();
    }

    public function count(?string $name = null): int
    {
        $query = Laboratory::query();

        if ($name !== null && $name !== '') {
            $query->where('name', 'LIKE', "%{$name}%");
        }

        return $query->count();
    }

    public function save(DomainLaboratory $laboratory): DomainLaboratory
    {
        return DB::transaction(function () use ($laboratory): DomainLaboratory {
            if ($laboratory->id() === null) {
                $model = Laboratory::create([
                    'name'        => $laboratory->name()->value(),
                    'country'     => $laboratory->country()->value(),
                    'is_active'   => $laboratory->isActive()->value(),
                ]);
            } else {
                $model = Laboratory::findOrFail($laboratory->id()->value());
                $model->update([
                    'name'        => $laboratory->name()->value(),
                    'country'     => $laboratory->country()->value(),
                    'is_active'   => $laboratory->isActive()->value(),
                ]);
            }

            return LaboratoryMapper::toDomain($model->fresh());
        });
    }

    public function delete(LaboratoryId $id): void
    {
        Laboratory::where('id', $id->value())->delete();
    }
}
