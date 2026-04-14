<?php

# app/Presentation/Http/Controllers/V1/UnitOfMeasureController.php

namespace App\Presentation\Http\Controllers\V1;

use App\Application\UnitOfMeasure\DTOs\V1\CreateUnitOfMeasureDTO;
use App\Application\UnitOfMeasure\DTOs\V1\ListUnitOfMeasuresDTO;
use App\Application\UnitOfMeasure\DTOs\V1\UpdateUnitOfMeasureDTO;
use App\Application\UnitOfMeasure\UseCases\V1\CreateUnitOfMeasure;
use App\Application\UnitOfMeasure\UseCases\V1\DeleteUnitOfMeasure;
use App\Application\UnitOfMeasure\UseCases\V1\ListUnitOfMeasure;
use App\Application\UnitOfMeasure\UseCases\V1\ShowUnitOfMeasure;
use App\Application\UnitOfMeasure\UseCases\V1\UpdateUnitOfMeasure;
use App\Http\Controllers\Controller;
use App\Infrastructure\Services\ApiResponseService;
use App\Presentation\DTOs\V1\UnitOfMeasure\UnitOfMeasureListResponseDTO;
use App\Presentation\DTOs\V1\UnitOfMeasure\UnitOfMeasureResponseDTO;
use App\Presentation\Http\Requests\V1\UnitOfMeasure\UnitOfMeasureIndexRequest;
use App\Presentation\Http\Requests\V1\UnitOfMeasure\UnitOfMeasureStoreRequest;
use App\Presentation\Http\Requests\V1\UnitOfMeasure\UnitOfMeasureUpdateRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\Middleware;

class UnitOfMeasureController extends Controller
{
     public function __construct(
       private readonly CreateUnitOfMeasure $create,
        private readonly UpdateUnitOfMeasure $update,
        private readonly DeleteUnitOfMeasure $delete,
        private readonly ListUnitOfMeasure $list,
        private readonly ShowUnitOfMeasure $show,
        protected ApiResponseService $api,
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware('permission:unit-of-measure-list', only: ['index']),
            new Middleware('permission:unit-of-measure-view', only: ['show']),
            new Middleware('permission:unit-of-measure-create', only: ['store']),
            new Middleware('permission:unit-of-measure-edit', only: ['update']),
            new Middleware('permission:unit-of-measure-delete', only: ['destroy']),
        ];
    }

    public function index(UnitOfMeasureIndexRequest $request)
    {
        $dto    = ListUnitOfMeasuresDTO::fromArray($request->validated());
        $result = $this->list->execute($dto);

        $responseDto = UnitOfMeasureListResponseDTO::fromPaginatedResult($result);

        return $this->api->success(
            $responseDto->toArray(),
            'Unit of measure list retrieved successfully'
        );
    }

    public function store(UnitOfMeasureStoreRequest $request): Response
    {
        $dto = CreateUnitOfMeasureDTO::fromArray($request->validated());
        $result = $this->create->execute($dto);

        return $this->api->success(
            UnitOfMeasureResponseDTO::fromDomain($result),
            'Unidad de medida creada correctamente',
            Response::HTTP_CREATED
        );
    }

    public function show(int $id)
    {
        try {
            $unitOfMeasure = $this->show->execute($id);

            return $this->api->success(
                UnitOfMeasureResponseDTO::fromEntity($unitOfMeasure)->toArray(),
                'Unit of measure found successfully',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

    public function update(UnitOfMeasureUpdateRequest $request, int $id)
    {
        try {
            $data          = array_merge($request->validated(), ['id' => $id]);
            $dto           = UpdateUnitOfMeasureDTO::fromArray($data);
            $unitOfMeasure = $this->update->execute($dto);

            return $this->api->success(
                UnitOfMeasureResponseDTO::fromEntity($unitOfMeasure)->toArray(),
                'Unit of measure updated successfully',
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
                'Unit of measure deleted successfully',
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return $this->api->error($e);
        }
    }

}