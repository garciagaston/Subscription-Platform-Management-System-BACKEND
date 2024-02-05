<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"name", "email", "password"},
 * )
 */
class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * @OA\Property(format="string", property="name", description="user fullname", example="Jon Snow", nullable=false)
     * @OA\Property(format="string", property="email", description="email", example="jon.snow@example.com", nullable=false)
     * @OA\Property(format="string", property="password", description="user password", example="password1234", nullable=false)
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:250',
            'email' => 'required|string|email:rfc,dns|max:250|unique:users,email',
            'password' => 'required|string|min:8|max:50|confirmed'
        ];
    }
}
