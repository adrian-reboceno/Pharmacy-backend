<?php
# app/Presentation/Http/Resources/V1/Permission/PermissionResource.php

namespace App\Presentation\Http\Resources\V1\Permission;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PermissionResource
 *
 * Transforms a Permission model instance into a JSON representation
 * for API responses in version 1 of the application.
 *
 * This resource ensures a consistent structure for permission data
 * when returned to API clients, improving maintainability and clarity
 * across the presentation layer.
 *
 * Example output:
 * {
 *     "id": 1,
 *     "name": "edit_user",
 *     "guard_name": "web"
 * }
 *
 * @package App\Presentation\Http\Resources\V1\Permission
 */
class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array for JSON serialization.
     *
     * @param \Illuminate\Http\Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'guard_name' => $this->guard_name,
        ];
    }
}
