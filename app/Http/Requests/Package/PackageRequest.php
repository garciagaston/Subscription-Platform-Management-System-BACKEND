<?php

namespace App\Http\Requests\Package;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"name", "sku", "image_url", "active"},
 * )
 */
class PackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @OA\Property(format="string", property="name", description="package name", example="HBO", nullable=false)
     * @OA\Property(format="string", property="description", description="package description", example="description", nullable=false)
     * @OA\Property(format="string", property="image_url", description="image_url", example="https://via.placeholder.com/640x480.png/00aa22?text=illum", nullable=false)
     * @OA\Property(format="string", property="sku", description="sku", example="SKU1234", nullable=false)
     * @OA\Property(format="boolean", property="active", description="active", example="1", nullable=false)
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:250',
            'description' => 'required|string|max:250',
            'sku' => 'required|string|max:250',
            'image_url' => 'required|string|url',
            'active' => 'required|boolean',
        ];
    }
}
