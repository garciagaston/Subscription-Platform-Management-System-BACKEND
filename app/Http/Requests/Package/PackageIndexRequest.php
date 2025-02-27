<?php

namespace App\Http\Requests\Package;

use Illuminate\Foundation\Http\FormRequest;

class PackageIndexRequest extends FormRequest
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
            'name' => 'sometimes|nullable|string',
            'sku' => 'sometimes|nullable|string',
            'active' => 'sometimes|nullable|boolean',
        ];
    }
}
