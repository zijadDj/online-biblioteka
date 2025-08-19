<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'script' => 'required|string|in:latin,arabic,cyrillic',
            'publisher' => 'required|string',
            'dimensions' => ['required', 'string', Rule::in('A1', 'A2', '21cm x 29.7cm', '15cm x 21cm')],
            'isbn' => 'required|string|digits:13|unique:books,isbn',
            'binding' => 'required|string|in:spiral_bound,paperback,hardcover',
            'page_count' => 'required|integer',
            'unit_count' => 'required|integer',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ];
    }
}
