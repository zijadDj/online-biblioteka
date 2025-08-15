<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->route('user'))],
            'jmbg' => ['required', 'digits:13', Rule::unique('users', 'jmbg')->ignore($this->route('user'))],
            'photo' => 'nullable|image|max:5120', // max 5MB
            'role' => 'required|in:student,librarian',
            'password'   => 'required|string|min:6',
        ];
    }
}
