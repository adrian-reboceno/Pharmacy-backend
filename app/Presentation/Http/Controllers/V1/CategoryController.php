<?php
# app/Presentation/Http/Controllers/V1/CategoryController.php

namespace App\Presentation\Http\Controllers\V1;

use App\Application\Category\DTOs\V1\CreateCategoryDTO;
use App\Application\Category\DTOs\V1\ListCategoriesDTO;
use App\Application\Category\DTOs\V1\UpdateCategoryDTO;
use App\Application\Category\UseCases\V1\CreateCategory;
use App\Application\Category\UseCases\V1\DeleteCategory;
use App\Application\Category\UseCases\V1\ListCategories;
use App\Application\Category\UseCases\V1\ShowCategory;
use App\Application\Category\UseCases\V1\UpdateCategory;
use App\Http\Controllers\Controller;
use App\Infrastructure\Services\ApiResponseService;
use App\Presentation\DTOs\V1\Category\CategoryListResponseDTO;
use App\Presentation\DTOs\V1\Category\CategoryResponseDTO;
use App\Presentation\Http\Requests\V1\Category\CategoryIndexRequest;
use App\Presentation\Http\Requests\V1\Category\CategoryStoreRequest;
use App\Presentation\Http\Requests\V1\Category\CategoryUpdateRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\Middleware;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CreateCategory $create,
        private readonly UpdateCategory $update,
        private readonly DeleteCategory $delete,
        private readonly ListCategories $list,
        private readonly ShowCategory $show,
        protected ApiResponseService $api,
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware('permission:category-list', only: ['index']),
            new Middleware('permission:category-view', only: ['show']),
            new Middleware('permission:category-create', only: ['store']),
            new Middleware('permission:category-edit', only: ['update']),
            new Middleware('permission:category-delete', only: ['destroy']),
        ];
    }

    public function index(CategoryIndexRequest $request)
    {
        $dto    = ListCategoriesDTO::fromArray($request->validated());
        $result = $this->list->execute($dto);

        $responseDto = CategoryListResponseDTO::fromPaginatedResult($result);

        return $this->api->success(
            $responseDto->toArray(),
            'Category list retrieved successfully'
        );
    }

    public function show(int $id)
    {
        try {
            $category = $this->show->execute($id);

            return $this->api->success(
                CategoryResponseDTO::fromEntity($category)->toArray(),
                'Category found successfully',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    public function store(CategoryStoreRequest $request)
    {
        try {
            $dto      = CreateCategoryDTO::fromArray($request->validated());
            $category = $this->create->execute($dto);

            return $this->api->success(
                CategoryResponseDTO::fromEntity($category)->toArray(),
                'Category created successfully',
                Response::HTTP_CREATED
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    public function update(CategoryUpdateRequest $request, int $id)
    {
        try {
            $data     = array_merge($request->validated(), ['id' => $id]);
            $dto      = UpdateCategoryDTO::fromArray($data);
            $category = $this->update->execute($dto);

            return $this->api->success(
                CategoryResponseDTO::fromEntity($category)->toArray(),
                'Category updated successfully',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->delete->execute($id);

            return $this->api->success(
                [],
                'Category deleted successfully',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }
}
