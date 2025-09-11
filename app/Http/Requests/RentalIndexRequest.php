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
            'user_id' => 'nullable|integer|exists:users,id',
            'book_title' => 'nullable|string|max:255',
            'book_id' => 'nullable|integer|exists:books,id',
            'per_page' => 'nullable|in:20,50,100',
        ];
    }
}
