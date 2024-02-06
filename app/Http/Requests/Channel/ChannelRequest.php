<?php

namespace App\Http\Requests\Channel;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"name", "image_url", "lineup_id", "call_sign", "active", "display_order"},
 * )
 */
class ChannelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @OA\Property(format="string", property="name", description="channel name", example="HBO", nullable=false)
     * @OA\Property(format="string", property="image_url", description="image_url", example="https://via.placeholder.com/640x480.png/00aa22?text=illum", nullable=false)
     * @OA\Property(format="integer", property="lineup_id", description="lineup_id", example="1", nullable=false)
     * @OA\Property(format="string", property="call_sign", description="call_sign", example="HBO", nullable=false)
     * @OA\Property(format="boolean", property="active", description="active", example="1", nullable=false)
     * @OA\Property(format="integer", property="display_order", description="display_order", example="1", nullable=false)
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:250',
            'image_url' => 'required|string|url',
            'lineup_id' => 'required|integer',
            'call_sign' => 'required|string',
            'active' => 'required|boolean',
            'display_order' => 'required|integer'
        ];
    }
}
