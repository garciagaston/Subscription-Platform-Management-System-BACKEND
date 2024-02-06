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
 * @property string $image_url
 * @property int $lineup_id
 * @property string $call_sign
 * @property bool $active
 * @property int $display_order
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class ChannelResource extends JsonResource
{
    /**
     * @OA\Property(format="int64",  property="id", title="id",description="id",default=1),
     * @OA\Property(format="string", property="name", title="name", description="TV channel name", default="HBO"),
     * @OA\Property(format="string", property="image_url", title="image_url", description="TV channel image url", default="https://via.placeholder.com/640x480.png/0011dd?text=eos"),
     * @OA\Property(format="int64", property="lineup_id", title="lineup_id", description="TV channel lineup id", default=1),
     * @OA\Property(format="string", property="call_sign", title="call_sign", description="TV channel call sign", default="HBO"),
     * @OA\Property(format="boolean", property="active", title="active", description="TV channel active", default=true),
     * @OA\Property(format="int64", property="display_order", title="display_order", description="TV channel display order", default=1),
     * @OA\Property(format="string", property="created_at", title="created_at", description="TV channel created at", default="2024-01-01 00:00:00"),
     * @OA\Property(format="string", property="updated_at", title="updated_at", description="TV channel updated at", default="2024-01-01 00:00:00"),
     * @OA\Property(format="string", property="deleted_at", title="deleted_at", description="TV channel deleted at", default="2024-01-01 00:00:00"),
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image_url' => $this->image_url,
            'lineup_id' => $this->lineup_id,
            'call_sign' => $this->call_sign,
            'active' => $this->active,
            'display_order' => $this->display_order,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
            'deleted_at' => optional($this->deleted_at)->toDateTimeString(),
        ];
    }
}
