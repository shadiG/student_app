<?php

namespace App\Http\Requests\V1\Classroom;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClassroomRequest extends FormRequest
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
        if ($this->method() == 'PUT') {
            return [
                'name' => ['required', Rule::unique('classrooms')->ignore($this->id),],
                'degree_id' => ['required', 'exists:degrees,id']
            ];
        } else {
            return [
                'name' => ['sometimes', 'required', Rule::unique('classrooms')->ignore($this->id)],
                'degree_id' => ['sometimes', 'required', 'exists:degrees,id']
            ];
        }
    }
}
