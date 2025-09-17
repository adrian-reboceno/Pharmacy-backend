<?php
#app/Traits/ExceptionHandlerTrait.php
namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Throwable;

trait ExceptionHandlerTrait
{
    use ApiResponseTrait;

    protected function handleException(Throwable $e, int $status = 500): JsonResponse
    {
        return $this->errorResponse(
            $e->getMessage(),
            $status
        );
    }
}
