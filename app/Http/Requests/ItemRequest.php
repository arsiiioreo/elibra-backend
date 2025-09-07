<?php

namespace App\Http\Requests;

use App\Models\Item;
use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Item::class) || $this->user()->can('update', Item::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id'            => 'nullable',
            'title'         => 'required|string|max:255',
            'authors'        => 'required|string|max:255',
            'publisher'     => 'required|string|max:255',
            'date_published' => 'required|date|before_or_equal:today',
            'call_number'   => 'nullable|string|max:100',
            'type'          => 'required|string',
            'campus_id'     => 'nullable|exists:campuses,id',
            'description'    => 'nullable|string|max:1000',
            'copies'        => 'required|min:1',
        ];
    }
}
