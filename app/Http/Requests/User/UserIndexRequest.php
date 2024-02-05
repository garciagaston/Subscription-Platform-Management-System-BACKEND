<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserIndexRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'page' => 'sometimes|nullable|int',
            'per_page' => 'sometimes|nullable|int',
        ];
    }
}
