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
 * @property string $description
 * @property string $sku
 * @property string $image_url
 * @property string $active
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property object $channels
 */
class PackageResource extends JsonResource
{
    /**
     * @OA\Property(format="int64",  property="id", title="id", description="id", default=1),
     * @OA\Property(format="string", property="name", title="name", description="Package name", default="Package 1"),
     * @OA\Property(format="string", property="description", title="description", description="Package description", default="Package description"),
     * @OA\Property(format="string", property="sku", title="sku", description="sku", default="PACKAGE1234"),
     * @OA\Property(format="string", property="image_url", title="image_url", description="Package image url", default="https://via.placeholder.com/640x480.png/0011dd?text=eos"),
     * @OA\Property(format="boolean", property="active", title="active", description="Package active", default=true),
     * @OA\Property(format="string", property="created_at", title="created_at", description="Package created at", default="2024-01-01 00:00:00"),
     * @OA\Property(format="string", property="updated_at", title="updated_at", description="Package updated at", default="2024-01-01 00:00:00"),
     * @OA\Property(format="string", property="deleted_at", title="deleted_at", description="Package deleted at", default="2024-01-01 00:00:00"),
     * @OA\Property(format="array", type="array", property="channels", description="channels", @OA\Items( ref="#/components/schemas/ChannelResource") ),
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'sku' => $this->sku,
            'image_url' => $this->image_url,
            'active' => $this->active,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
            'deleted_at' => optional($this->deleted_at)->toDateTimeString(),
            'channels' => ChannelResource::collection(
                $this->channels
            )->toArray(new Request),
        ];
    }
}
