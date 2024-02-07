<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 * )
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property object $roles
 * @property array $permissions
 * @property array $subscriptions
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class UserResource extends JsonResource
{
    /**
     * @OA\Property(format="int64",  property="id", title="id",description="id",default=1),
     * @OA\Property(format="string", property="name", title="name", description="user fullname", default="jon snow"),
     * @OA\Property(format="string", property="email", title="email", description="email", default="jon.snow@example.com"),
     * @OA\Property(format="array", property="roles", title="role", description="role", default="[]"),
     * @OA\Property(format="array", property="permissions", title="permissions", description="permissions", default="[]"),
     * @OA\Property(format="array", type="array", property="subscriptions", description="subscriptions", @OA\Items( ref="#/components/schemas/SubscriptionResource") ),
     * @OA\Property(format="date", property="created_at", title="created_at", description="created_at", default="2024-01-29 15:21:29"),
     * @OA\Property(format="date", property="updated_at", title="updated_at", description="updated_at", default="2024-01-29 15:21:29"),
     * @OA\Property(format="date", property="deleted_at", title="deleted_at", description="deleted_at", default="2024-01-29 15:21:29"),
     *
     * @param Request $request
     * @return array
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
            'permissions' => count($permissions) ? $permissions : null,
            'subscriptions' => SubscriptionResource::collection(
                $this->subscriptions
            )->toArray(new Request()),
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
            'deleted_at' => optional($this->deleted_at)->toDateTimeString(),
        ];
    }
}
