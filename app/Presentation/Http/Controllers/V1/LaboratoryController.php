<?php
# app/Presentation/Http/Controllers/V1/LaboratoryController.php

namespace App\Presentation\Http\Controllers\V1;

use App\Application\Laboratory\DTOs\V1\CreateLaboratoryDTO;
use App\Application\Laboratory\DTOs\V1\ListLaboratoriesDTO;
use App\Application\Laboratory\DTOs\V1\UpdateLaboratoryDTO;
use App\Application\Laboratory\UseCases\V1\CreateLaboratory;
use App\Application\Laboratory\UseCases\V1\DeleteLaboratory;
use App\Application\Laboratory\UseCases\V1\ListLaboratories;
use App\Application\Laboratory\UseCases\V1\ShowLaboratory;
use App\Application\Laboratory\UseCases\V1\UpdateLaboratory;
use App\Http\Controllers\Controller;
use App\Infrastructure\Services\ApiResponseService;
use App\Presentation\DTOs\V1\Laboratory\LaboratoryListResponseDTO;
use App\Presentation\DTOs\V1\Laboratory\LaboratoryResponseDTO;
use App\Presentation\Http\Requests\V1\Laboratory\LaboratoryIndexRequest;
use App\Presentation\Http\Requests\V1\Laboratory\LaboratoryStoreRequest;
use App\Presentation\Http\Requests\V1\Laboratory\LaboratoryUpdateRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\Middleware;

class LaboratoryController extends Controller
{
    public function __construct(
        private readonly CreateLaboratory $create,
        private readonly UpdateLaboratory $update,
        private readonly DeleteLaboratory $delete,
        private readonly ListLaboratories $list,
        private readonly ShowLaboratory $show,
        protected ApiResponseService $api,
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware('permission:laboratory-list', only: ['index']),
            new Middleware('permission:laboratory-view', only: ['show']),
            new Middleware('permission:laboratory-create', only: ['store']),
            new Middleware('permission:laboratory-edit', only: ['update']),
            new Middleware('permission:laboratory-delete', only: ['destroy']),
        ];
    }

    public function index(LaboratoryIndexRequest $request)
    {
        $dto    = ListLaboratoriesDTO::fromArray($request->validated());
        $result = $this->list->execute($dto);

        $responseDto = LaboratoryListResponseDTO::fromPaginatedResult($result);

        return $this->api->success(
            $responseDto->toArray(),
            'Laboratory list retrieved successfully'
        );
    }

    public function show(int $id)
    {
        try {
            $laboratory = $this->show->execute($id);

            return $this->api->success(
                LaboratoryResponseDTO::fromEntity($laboratory)->toArray(),
                'Laboratory found successfully',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    public function store(LaboratoryStoreRequest $request)
    {
        try {
            $dto         = CreateLaboratoryDTO::fromArray($request->validated());
            $laboratory = $this->create->execute($dto);

            return $this->api->success(
                LaboratoryResponseDTO::fromEntity($laboratory)->toArray(),
                'Laboratory created successfully',
                Response::HTTP_CREATED
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    public function update(LaboratoryUpdateRequest $request, int $id)
    {
        try {
            $data        = array_merge($request->validated(), ['id' => $id]);
            $dto         = UpdateLaboratoryDTO::fromArray($data);
            $laboratory = $this->update->execute($dto);

            return $this->api->success(
                LaboratoryResponseDTO::fromEntity($laboratory)->toArray(),
                'Laboratory updated successfully',
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
                'Laboratory deleted successfully',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }
}
    