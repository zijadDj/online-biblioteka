<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'description' => 'required|string',
            'book_ids' => 'sometimes|array',
            'book_ids.*' => 'integer|exists:books,id',
            'remove_book_ids' => 'sometimes|array',
            'remove_book_ids.*' => 'integer|exists:books,id'
        ];
    }
}

