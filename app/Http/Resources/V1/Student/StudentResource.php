<?php

namespace App\Http\Resources\V1\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\Classroom\ClassroomResource;
use App\Http\Resources\V1\Degree\DegreeResource;

/**
 * @OA\Schema
 */
class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'classroom' => new ClassroomResource($this->whenLoaded('classroom')),
            'degree' => new DegreeResource($this->whenLoaded('degree'))
        ];
    }
}
