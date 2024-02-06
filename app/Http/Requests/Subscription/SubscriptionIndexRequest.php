<?php

namespace App\Http\Requests\Subscription;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionIndexRequest extends FormRequest
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
            'user_id' => 'sometimes|nullable|int|exists:App\Models\User,id',
            'package_id' => 'sometimes|nullable|int|exists:App\Models\Package,id',
            'active' => 'sometimes|nullable|boolean'
        ];
    }
}
