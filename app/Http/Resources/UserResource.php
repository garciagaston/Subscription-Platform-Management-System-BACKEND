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
     * @OA\Property(format="int64", title="id",description="id", property="id",  default=1),
     * @OA\Property(format="string", title="name", description="name", property="name", default="name"),
     * @OA\Property(format="string", title="email", description="email", property="email", default="name@domain.com"),
     * @OA\Property(format="array", title="role", description="role", property="roles", default="[]"),
     * @OA\Property(format="array", title="permissions", description="permissions", property="permissions", default="[]"),
     * @OA\Property(format="date", title="created_at", description="created_at", property="created_at", default="2022-09-29 15:21:29"),
     * @OA\Property(format="date", title="updated_at", description="updated_at", property="updated_at", default="2022-09-29 15:21:29"),
     * @OA\Property(format="date", title="deleted_at", description="deleted_at", property="deleted_at", default="2022-09-29 15:21:29"),
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray(Request $request): array
    {
        $permissions = null;
        if (is_null($this->hidePerms) || $this->hidePerms === false) {
            $permissions = collect($this->getAllPermissions()->toArray())->map(function ($permission) {
                return [
                    'id' => $permission['id'],
                    'name' => $permission['name'],
                ];
            })->toArray();
        }

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
