<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"email", "password"},
 * )
 */
class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * @OA\Property(format="string", property="email", description="email", example="jon.snow@example.com", nullable=false)
     * @OA\Property(format="string", property="password", description="user password", example="password1234", nullable=false)
     */
    public function rules()
    {
        return [
            'email' => 'required|string|email|max:250',
            'password' => 'required|string|min:8|max:50',
        ];
    }
}
