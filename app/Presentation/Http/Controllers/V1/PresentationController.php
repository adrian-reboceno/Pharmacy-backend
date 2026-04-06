<?php
# app/Presentation/Http/Controllers/V1/PresentationController.php

namespace App\Presentation\Http\Controllers\V1;

use App\Application\Presentation\DTOs\V1\CreatePresentationDTO;
use App\Application\Presentation\DTOs\V1\ListPresentationsDTO;
use App\Application\Presentation\DTOs\V1\UpdatePresentationDTO;
use App\Application\Presentation\UseCases\V1\CreatePresentation;
use App\Application\Presentation\UseCases\V1\DeletePresentation;
use App\Application\Presentation\UseCases\V1\ListPresentations;
use App\Application\Presentation\UseCases\V1\ShowPresentation;
use App\Application\Presentation\UseCases\V1\UpdatePresentation;
use App\Http\Controllers\Controller;
use App\Infrastructure\Services\ApiResponseService;
use App\Presentation\DTOs\V1\Presentation\PresentationListResponseDTO;
use App\Presentation\DTOs\V1\Presentation\PresentationResponseDTO;
use App\Presentation\Http\Requests\V1\Presentation\PresentationIndexRequest;
use App\Presentation\Http\Requests\V1\Presentation\PresentationStoreRequest;
use App\Presentation\Http\Requests\V1\Presentation\PresentationUpdateRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\Middleware;

class PresentationController extends Controller
{
    public function __construct(
        private readonly CreatePresentation $create,
        private readonly UpdatePresentation $update,
        private readonly DeletePresentation $delete,
        private readonly ListPresentations $list,
        private readonly ShowPresentation $show,
        protected ApiResponseService $api,
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware('permission:presentation-list', only: ['index']),
            new Middleware('permission:presentation-view', only: ['show']),
            new Middleware('permission:presentation-create', only: ['store']),
            new Middleware('permission:presentation-edit', only: ['update']),
            new Middleware('permission:presentation-delete', only: ['destroy']),
        ];
    }

    public function index(PresentationIndexRequest $request)
    {
        $dto    = ListPresentationsDTO::fromArray($request->validated());
        $result = $this->list->execute($dto);

        $responseDto = PresentationListResponseDTO::fromPaginatedResult($result);

        return $this->api->success(
            $responseDto->toArray(),
            'Presentation list retrieved successfully'
        );
    }

    public function show(int $id)
    {
        try {
            $presentation = $this->show->execute($id);

            return $this->api->success(
                PresentationResponseDTO::fromEntity($presentation)->toArray(),
                'Presentation found successfully',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    public function store(PresentationStoreRequest $request)
    {
        try {
            $dto          = CreatePresentationDTO::fromArray($request->validated());
            $presentation = $this->create->execute($dto);

            return $this->api->success(
                PresentationResponseDTO::fromEntity($presentation)->toArray(),
                'Presentation created successfully',
                Response::HTTP_CREATED
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    public function update(PresentationUpdateRequest $request, int $id)
    {
        try {
            $data          = array_merge($request->validated(), ['id' => $id]);
            $dto           = UpdatePresentationDTO::fromArray($data);
            $presentation = $this->update->execute($dto);

            return $this->api->success(
                PresentationResponseDTO::fromEntity($presentation)->toArray(),
                'Presentation updated successfully',
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
                'Presentation deleted successfully',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }
}