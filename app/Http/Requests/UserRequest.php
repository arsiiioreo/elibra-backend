<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'nullable',
            'name' => 'required|string|max:255',
            'sex' => 'required|in:0,1', // 0: Female, 1: Male
            'contact_number' => 'nullable|string|max:13',
            'email' => 'required|email|unique:users,email,' . auth('api')->id(),
            'password' => 'nullable|string|min:8',
            'campus_id' => 'nullable|exists:campuses,id',
            'role' => 'required|in:0,1,2,3', // 0: Admin, 1: Librarian, 2: Patron, 3: Student Assistant
            'profile_picture' => 'nullable|exists:profile_photos,id',
        ];
    }
}
