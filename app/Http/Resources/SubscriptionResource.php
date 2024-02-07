<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 * )
 *
 * @property int $id
 * @property int $user_id
 * @property int $package_id
 * @property string $start_date
 * @property string $end_date
 * @property bool $active
 * @property object $package
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class SubscriptionResource extends JsonResource
{
    /**
     * @OA\Property(format="int64",  property="id", title="id", description="id", default=1),
     * @OA\Property(format="integer", property="user_id", title="user_id", description="user_id", default="1"),
     * @OA\Property(format="integer", property="package_id", title="package_id", description="package_id", default="1"),
     * @OA\Property(format="string", property="start_date", title="start_date", description="start_date", default="2024-01-01 00:00:00"),
     * @OA\Property(format="string", property="end_date", title="end_date", description="end_date", default="2024-01-01 00:00:00"),
     * @OA\Property(format="boolean", property="active", title="active", description="Package active", default=true),
     * @OA\Property(property="package", ref="#/components/schemas/PackageResource")
     * @OA\Property(format="string", property="created_at", title="created_at", description="Package created at", default="2024-01-01 00:00:00"),
     * @OA\Property(format="string", property="updated_at", title="updated_at", description="Package updated at", default="2024-01-01 00:00:00"),
     * @OA\Property(format="string", property="deleted_at", title="deleted_at", description="Package deleted at", default="2024-01-01 00:00:00"),
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'package_id' => $this->package_id,
            'start_date' => optional($this->start_date)->toDateTimeString(),
            'end_date' => optional($this->end_date)->toDateTimeString(),
            'active' => $this->active,
            'package' => (new PackageResource($this->package))->toArray(new Request()),
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
            'deleted_at' => optional($this->deleted_at)->toDateTimeString(),
        ];
    }
}
