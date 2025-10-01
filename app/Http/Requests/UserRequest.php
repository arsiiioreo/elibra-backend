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
        'name' => 'sometimes|string|max:255',
        'sex' => 'sometimes|nullable|in:0,1', 
        'contact_number' => 'sometimes|nullable|string|max:13',
        'email' => 'sometimes|email|unique:users,email,' . auth('api')->id(),
        'password' => 'sometimes|nullable|string|min:8',
        'campus_id' => 'sometimes|nullable|exists:campuses,id',
        'role' => 'sometimes|in:0,1,2,3',
        // 'profile_picture' => 'sometimes|nullable|file|image|max:2048', -> OLD
        'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048' //NEW

    ];
}

}
