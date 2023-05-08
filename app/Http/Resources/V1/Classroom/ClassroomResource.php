<?php

namespace App\Http\Resources\V1\Classroom;

use App\Http\Resources\V1\Student\StudentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\Degree\DegreeResource;

/**
 * @OA\Schema
 */
class ClassroomResource extends JsonResource
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
          'degree' => new DegreeResource($this->whenLoaded('degree')),
          'students' => StudentResource::collection($this->whenLoaded('students')),
        ];
    }
}
