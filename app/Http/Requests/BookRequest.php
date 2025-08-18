<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
            'language' => 'required|string',
            'script' => 'required|string',
            'publisher' => 'required|string',
            'dimensions' => 'required|string',
            'isbn' => 'required|string|digits:13|unique:books,isbn',
            'binding' => 'required|string',
            'page_count' => 'required|integer',
            'unit_count' => 'required|integer',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ];
    }
}
