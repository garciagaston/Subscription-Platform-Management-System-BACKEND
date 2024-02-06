<?php

namespace App\Http\Requests\Subscription;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"user_id", "package_id", "start_date", "end_date", "active"},
 * )
 */
class SubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @OA\Property(format="integer", property="user_id", description="user_id", example="1", nullable=false)
     * @OA\Property(format="integer", property="package_id", description="package_id", example="1", nullable=false)
     * @OA\Property(format="string", property="start_date", description="start_date", example="2024-01-01", nullable=false)
     * @OA\Property(format="string", property="end_date", description="end_date", example="2024-01-01", nullable=false)
     * @OA\Property(format="boolean", property="active", description="active", example="true", nullable=false)
     */
    public function rules(): array
    {
        return [
            'user_id' => 'sometimes|nullable|int|exists:App\Models\User,id',
            'package_id' => 'sometimes|nullable|int|exists:App\Models\Package,id',
            'start_date' => 'required|string|date|before:end_date',
            'end_date' => 'required|string|date|after:start_date',
            'active' => 'sometimes|nullable|boolean'
        ];
    }
}
