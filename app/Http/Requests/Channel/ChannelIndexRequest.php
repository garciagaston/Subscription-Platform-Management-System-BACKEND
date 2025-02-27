<?php

namespace App\Http\Requests\Channel;

use Illuminate\Foundation\Http\FormRequest;

class ChannelIndexRequest extends FormRequest
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
            'call_sign' => 'sometimes|nullable|string',
            'active' => 'sometimes|nullable|boolean',
        ];
    }
}
