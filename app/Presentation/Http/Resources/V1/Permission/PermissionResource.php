<?php
#app/Presentation/Http/Resources/V1/Permission/PermissionResource.php;
namespace App\Presentation\Http\Resources\V1\Permission;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    public function toArray($request): array
    {
         return [
            'id'   => $this->id,
            'name' => $this->name,
            'guard_name' => $this->guard_name,            
        ];
    }
}
