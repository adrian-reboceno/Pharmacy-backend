<?php   
# app/Presentation/Http/Controllers/V1/PharmaceuticalFormController.php

namespace App\Presentation\Http\Controllers\V1;

use App\Application\PharmaceuticalForm\DTOs\V1\CreatePharmaceuticalFormDTO;
use App\Application\PharmaceuticalForm\DTOs\V1\ListPharmaceuticalFormsDTO;
use App\Application\PharmaceuticalForm\DTOs\V1\UpdatePharmaceuticalFormDTO;
use App\Application\PharmaceuticalForm\UseCases\V1\CreatePharmaceuticalForm;
use App\Application\PharmaceuticalForm\UseCases\V1\DeletePharmaceuticalForm;
use App\Application\PharmaceuticalForm\UseCases\V1\ListPharmaceuticalForms;
use App\Application\PharmaceuticalForm\UseCases\V1\ShowPharmaceuticalForm;
use App\Application\PharmaceuticalForm\UseCases\V1\UpdatePharmaceuticalForm;
use App\Http\Controllers\Controller;
use App\Infrastructure\Services\ApiResponseService;
use App\Presentation\DTOs\V1\PharmaceuticalForm\PharmaceuticalFormListResponseDTO;
use App\Presentation\DTOs\V1\PharmaceuticalForm\PharmaceuticalFormResponseDTO;
use App\Presentation\Http\Requests\V1\PharmaceuticalForm\PharmaceuticalFormIndexRequest;
use App\Presentation\Http\Requests\V1\PharmaceuticalForm\PharmaceuticalFormStoreRequest;
use App\Presentation\Http\Requests\V1\PharmaceuticalForm\PharmaceuticalFormUpdateRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\Middleware;

class PharmaceuticalFormController extends Controller
{
    public function __construct(
        private readonly CreatePharmaceuticalForm $create,
        private readonly UpdatePharmaceuticalForm $update,
        private readonly DeletePharmaceuticalForm $delete,
        private readonly ListPharmaceuticalForms $list,
        private readonly ShowPharmaceuticalForm $show,
        protected ApiResponseService $api,
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware('permission:pharmaceuticalforms-list', only: ['index']),
            new Middleware('permission:pharmaceuticalforms-view', only: ['show']),
            new Middleware('permission:pharmaceuticalforms-create', only: ['store']),
            new Middleware('permission:pharmaceuticalforms-edit', only: ['update']),
            new Middleware('permission:pharmaceuticalforms-delete', only: ['destroy']),
        ];
    }

    public function index(PharmaceuticalFormIndexRequest $request)
    {
        $dto    = ListPharmaceuticalFormsDTO::fromArray($request->validated());
        $result = $this->list->execute($dto);

        $responseDto = PharmaceuticalFormListResponseDTO::fromPaginatedResult($result);

        return $this->api->success(
            $responseDto->toArray(),
            'Pharmaceutical form list retrieved successfully'
        );
    }

    public function show(int $id)
    {
        try {
            $pharmaceuticalForm = $this->show->execute($id);

            return $this->api->success(
                PharmaceuticalFormResponseDTO::fromEntity($pharmaceuticalForm)->toArray(),
                'Pharmaceutical form found successfully',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    public function store(PharmaceuticalFormStoreRequest $request)
    {
        try {
            $dto         = CreatePharmaceuticalFormDTO::fromArray($request->validated());
            $pharmaceuticalForm = $this->create->execute($dto);

            return $this->api->success(
                PharmaceuticalFormResponseDTO::fromEntity($pharmaceuticalForm)->toArray(),
                'Pharmaceutical form created successfully',
                Response::HTTP_CREATED
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    public function update(PharmaceuticalFormUpdateRequest $request, int $id)
    {
        try {
            $data        = array_merge($request->validated(), ['id' => $id]);
            $dto         = UpdatePharmaceuticalFormDTO::fromArray($data);
            $pharmaceuticalForm = $this->update->execute($dto);

            return $this->api->success(
                PharmaceuticalFormResponseDTO::fromEntity($pharmaceuticalForm)->toArray(),
                'Pharmaceutical form updated successfully',
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
                'Pharmaceutical form deleted successfully',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }
}