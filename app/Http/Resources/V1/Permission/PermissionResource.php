<?php
#app/Http/Resources/V1/PermissionResource.php
namespace App\Http\Resources\V1\Permission;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'guard'      => $this->guard_name,
            //'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            //'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
