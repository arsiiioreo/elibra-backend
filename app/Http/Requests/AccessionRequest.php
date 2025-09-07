<?php

namespace App\Http\Requests;

use App\Models\Accession;
use Illuminate\Foundation\Http\FormRequest;

class AccessionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Accession::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'item_id'           => 'required|exists:items,id',
            'copies'            => 'required|integer|min:1',
        ];
    }
}
