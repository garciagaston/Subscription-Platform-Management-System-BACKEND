<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={},
 * )
 */
class UserUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * @OA\Property(format="string", property="name", description="user fullname", example="Jon Snow", nullable=false)
     * @OA\Property(format="string", property="email", description="email", example="jon.snow@example.com", nullable=false)
     * @OA\Property(format="string", property="password", description="user password", example="password1234", nullable=false)
     * @OA\Property(format="string", property="password_confirmation", description="user password confirmation", example="password1234", nullable=false)
     */
    public function rules()
    {
        $user = $this->route('user');

        return [
            'name' => 'sometimes|required|string|max:250',
            'email' => 'sometimes|required|string|email|max:250|unique:users,email,' . optional($user)->id,
            'password' => 'sometimes|required|string|min:8|max:50|confirmed',
        ];
    }
}
