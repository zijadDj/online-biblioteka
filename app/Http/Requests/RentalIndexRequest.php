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
            'search_user' => 'nullable|string|max:255',
            'per_page' => 'nullable|in:20,50,100',
        ];
    }
}
