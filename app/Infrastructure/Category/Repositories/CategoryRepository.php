<?php
# app/Infrastructure/Category/Repositories/CategoryRepository.php

namespace App\Infrastructure\Category\Repositories;

use App\Domain\Category\Entities\Category as DomainCategory;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Domain\Category\ValueObjects\CategoryId;
use App\Infrastructure\Category\Mappers\CategoryMapper;
use App\Infrastructure\Category\Models\Category;
use Illuminate\Support\Facades\DB;

final class CategoryRepository implements CategoryRepositoryInterface
{
    public function findById(CategoryId $id): ?DomainCategory
    {
        $model = Category::find($id->value());

        return $model ? CategoryMapper::toDomain($model) : null;
    }

    public function findByName(string $name): ?DomainCategory
    {
        $model = Category::where('name', $name)->first();

        return $model ? CategoryMapper::toDomain($model) : null;
    }

    /** @return DomainCategory[] */
    public function paginate(int $page, int $perPage, ?string $name = null): array
    {
        $query = Category::query();

        if ($name !== null && $name !== '') {
            $query->where('name', 'LIKE', "%{$name}%");
        }

        $models = $query
            ->orderBy('id')
            ->forPage($page, $perPage)
            ->get();

        return $models
            ->map(fn (Category $model) => CategoryMapper::toDomain($model))
            ->all();
    }

    public function count(?string $name = null): int
    {
        $query = Category::query();

        if ($name !== null && $name !== '') {
            $query->where('name', 'LIKE', "%{$name}%");
        }

        return $query->count();
    }

    public function save(DomainCategory $category): DomainCategory
    {
        return DB::transaction(function () use ($category): DomainCategory {
            if ($category->id() === null) {
                $model = Category::create([
                    'name'        => $category->name()->value(),
                    'description' => $category->description()?->value(),
                    'is_active'   => $category->isActive()->value(),
                ]);
            } else {
                $model = Category::findOrFail($category->id()->value());
                $model->update([
                    'name'        => $category->name()->value(),
                    'description' => $category->description()?->value(),
                    'is_active'   => $category->isActive()->value(),
                ]);
            }

            return CategoryMapper::toDomain($model->fresh());
        });
    }

    public function delete(CategoryId $id): void
    {
        Category::where('id', $id->value())->delete();
    }
}
