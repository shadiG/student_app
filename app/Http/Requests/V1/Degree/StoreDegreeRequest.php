<?php

namespace App\Http\Requests\V1\Degree;

use Illuminate\Foundation\Http\FormRequest;

class StoreDegreeRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'unique:degrees,name'],
            'max_year' => ['required', 'gt:0']
        ];
    }
}
