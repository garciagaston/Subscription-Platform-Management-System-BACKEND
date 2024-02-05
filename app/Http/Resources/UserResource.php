<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 * )
 */
class UserResource extends JsonResource
{
    /**
     * @OA\Property(format="int64",  property="id", title="id",description="id",default=1),
     * @OA\Property(format="string", property="name", title="name", description="user fullname", default="jon snow"),
     * @OA\Property(format="string", property="email", title="email", description="email", default="jon.snow@example.com"),
     * @OA\Property(format="array", property="roles", title="role", description="role", default="[]"),
     * @OA\Property(format="array", property="permissions", title="permissions", description="permissions", default="[]"),
     * @OA\Property(format="date", property="created_at", title="created_at", description="created_at", default="2024-01-29 15:21:29"),
     * @OA\Property(format="date", property="updated_at", title="updated_at", description="updated_at", default="2024-01-29 15:21:29"),
     * @OA\Property(format="date", property="deleted_at", title="deleted_at", description="deleted_at", default="2024-01-29 15:21:29"),
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray(Request $request): array
    {
        $permissions = collect($this->getAllPermissions()->toArray())->map(function ($permission) {
            return [
                'id' => $permission['id'],
                'name' => $permission['name'],
            ];
        })->toArray();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => (array) optional($this->roles->first())->only('id', 'name'),
            'permissions' => isset($permissions) ? (array) $permissions : null,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
            'deleted_at' => optional($this->deleted_at)->toDateTimeString(),
        ];
    }
}
