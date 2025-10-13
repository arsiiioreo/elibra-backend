<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegistrationRequest extends FormRequest
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
            'last_name'                 => ['required', 'string', 'max:255'],
            'first_name'                => ['required', 'string', 'max:255'],
            'middle_initial'            => ['nullable', 'string', 'max:255'],
            'sex'                       => ['required', 'in:male,female,others'],
            'campus_id'                 => ['nullable', 'exists:campuses,id'],
            'id_number'                 => ['nullable', 'string', 'max:50', Rule::unique('patrons', 'id_number')],
            'external_organization'     => ['nullable', 'in:1,2'],
            'role'                      => ['required', 'in:1,2'],
            'email'                     => ['required', 'email', Rule::unique('users', 'email'),],
            'password'                  => ['required', 'string', 'min:8', 'confirmed'],
            'patron_type'               => ['required', 'string', 'exists:patron_types,id'],
        ];
    }
}
