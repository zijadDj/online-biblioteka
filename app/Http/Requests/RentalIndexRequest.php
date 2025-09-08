<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RentalIndexRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'search' => 'nullable|string|max:255',
            'user_id' => 'nullable|integer|exists:users,id',
            'per_page' => 'nullable|in:20,50,100',
        ];
    }
}
