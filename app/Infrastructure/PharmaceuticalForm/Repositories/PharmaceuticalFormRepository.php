<?php

# app/Infrastructure/PharmaceuticalForm/Repositories/PharmaceuticalFormRepository.php

namespace App\Infrastructure\PharmaceuticalForm\Repositories;

use App\Domain\PharmaceuticalForm\Entities\PharmaceuticalForm as DomainPharmaceuticalForm;
use App\Domain\PharmaceuticalForm\Repositories\PharmaceuticalFormRepositoryInterface;
use App\Domain\PharmaceuticalForm\ValueObjects\PharmaceuticalFormId;
use App\Infrastructure\PharmaceuticalForm\Mappers\PharmaceuticalFormMapper;
use App\Infrastructure\PharmaceuticalForm\Models\PharmaceuticalForm as PharmaceuticalForm;
use Illuminate\Support\Facades\DB;

final class PharmaceuticalFormRepository implements PharmaceuticalFormRepositoryInterface
{
    public function findById(PharmaceuticalFormId $id): ?DomainPharmaceuticalForm
    {
        $model = PharmaceuticalForm::find($id->value());

        return $model ? PharmaceuticalFormMapper::toDomain($model) : null;
    }

    public function findByName(string $name): ?DomainPharmaceuticalForm
    {
        $model = PharmaceuticalForm::where('name', $name)->first();

        return $model ? PharmaceuticalFormMapper::toDomain($model) : null;
    }

    /** @return DomainPharmaceuticalForm[] */
    public function paginate(int $page, int $perPage, ?string $name = null): array
    {
        $query = PharmaceuticalForm::query();

        if ($name !== null && $name !== '') {
            $query->where('name', 'LIKE', "%{$name}%");
        }

        $models = $query
            ->orderBy('id')
            ->forPage($page, $perPage)
            ->get();

        return $models
            ->map(fn (PharmaceuticalForm $model) => PharmaceuticalFormMapper::toDomain($model))
            ->all();
    }

    public function count(?string $name = null): int
    {
        $query = PharmaceuticalForm::query();

        if ($name !== null && $name !== '') {
            $query->where('name', 'LIKE', "%{$name}%");
        }

        return $query->count();
    }

    public function save(DomainPharmaceuticalForm $presentation): DomainPharmaceuticalForm
    {
        return DB::transaction(function () use ($presentation): DomainPharmaceuticalForm {
            if ($presentation->id() === null) {
                $model = PharmaceuticalForm::create([
                    'name'        => $presentation->name()->value(),
                    'is_active'   => $presentation->isActive()->value(),
                ]);
            } else {
                $model = PharmaceuticalForm::findOrFail($presentation->id()->value());
                $model->update([
                    'name'        => $presentation->name()->value(),
                    'is_active'   => $presentation->isActive()->value(),
                ]);
            }

            return PharmaceuticalFormMapper::toDomain($model->fresh());
        });
    }

    public function delete(PharmaceuticalFormId $id): void
    {
        PharmaceuticalForm::where('id', $id->value())->delete();
    }
}
