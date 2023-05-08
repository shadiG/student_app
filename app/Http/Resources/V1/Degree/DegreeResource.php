<?php

namespace App\Http\Resources\V1\Degree;

use App\Http\Resources\V1\Student\StudentResource;
use App\Http\Resources\V1\Classroom\ClassroomResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema
 */
class DegreeResource extends JsonResource
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
            'name' => $this->name,
            'max_year' => $this->max_year,
            'classrooms' => ClassroomResource::collection($this->whenLoaded('classrooms')),
            'students' => StudentResource::collection($this->whenLoaded('students')),
        ];
    }
}
