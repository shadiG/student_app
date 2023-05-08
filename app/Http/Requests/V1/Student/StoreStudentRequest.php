<?php

namespace App\Http\Requests\V1\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


/**
 * @OA\Schema
 */
class StoreStudentRequest extends FormRequest
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
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required',Rule::unique('students')->ignore($this->id),],
            'gender' => ['required',Rule::in(['male', 'female']),],
            'date_of_birth' => ['date', 'before:2005-01-01']
        ];
    }
}
