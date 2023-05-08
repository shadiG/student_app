<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Classroom;
use App\Http\Requests\V1\Classroom\StoreClassroomRequest;
use App\Http\Requests\V1\Classroom\UpdateClassroomRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Classroom\ClassroomCollection;
use App\Http\Resources\V1\Classroom\ClassroomResource;
use Illuminate\Http\Request;
use App\Filters\V1\ClassroomFilter;
use Illuminate\Http\Response;

class ClassroomController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/classrooms",
     *     summary="List all classrooms",
     *     tags={"Classrooms"},
     *     @OA\Parameter(
     *         name="includeStudents",
     *         in="query",
     *         description="Whether to include the students in each classroom",
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="includeDegrees",
     *         in="query",
     *         description="Whether to include the degree of each classroom",
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="List of classrooms",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ClassroomResource")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $filter = new ClassroomFilter();
        $filterItems = $filter->transform($request);

        $classrooms = Classroom::where($filterItems);


        if (request()->boolean('includeDegrees')) {
            $classrooms->with('degree');
        }
        if (request()->boolean('includeStudents') === true) {
            $classrooms->with('students');
        }

        return new ClassroomCollection($classrooms->paginate()->appends($request->query()));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/classrooms",
     *     summary="Create a new classroom",
     *     tags={"Classrooms"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Classroom data",
     *         @OA\JsonContent(
     *             required={"name", "degree_id"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="degree_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="The created classroom",
     *         @OA\JsonContent(ref="#/components/schemas/ClassroomResource")
     *     )
     * )
     */
    public function store(StoreClassroomRequest $request)
    {
        return new ClassroomResource(Classroom::create($request->all()));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/classrooms/{classroom}",
     *     summary="Retrieve a single classroom",
     *     tags={"Classrooms"},
     *     @OA\Parameter(
     *         name="classroom",
     *         in="path",
     *         description="ID of the classroom to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="includeStudents",
     *         in="query",
     *         description="Whether to include the students in the classroom",
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="includeDegree",
     *         in="query",
     *         description="Whether to include the degree for the classroom",
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="The requested classroom",
     *         @OA\JsonContent(ref="#/components/schemas/ClassroomResource")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Classroom not found"
     *     )
     * )
     */
    public function show(Classroom $classroom)
    {
        if (request()->boolean('includeDegree')) {
            $classroom->loadMissing('degree');
        }
        if (request()->boolean('includeStudents')) {
            $classroom->loadMissing('students');
        }
        return new ClassroomResource($classroom);
    }


    /**
     * @OA\Put(
     *     path="/api/v1/classrooms/{id}",
     *     summary="Update a classroom with PUT request",
     *     tags={"Classrooms"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the classroom to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Classroom data",
     *         @OA\JsonContent(
     *             required={},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="degree_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Classroom updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ClassroomResource")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Classroom not found"
     *     )
     * )
     *
     * @OA\Patch(
     *     path="/api/v1/classrooms/{id}",
     *     summary="Update a classroom with PATCH request",
     *     tags={"Classrooms"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the classroom to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Classroom data",
     *         @OA\JsonContent(
     *             required={},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="degree_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Classroom updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ClassroomResource")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Classroom not found"
     *     )
     * )
     */
    public function update(UpdateClassroomRequest $request, Classroom $classroom)
    {
        $classroom->fill($request->all())->save();
        return new ClassRoomResource($classroom);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/classrooms/{id}",
     *     summary="Delete a classroom",
     *     tags={"Classrooms"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the classroom to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="forceDelete",
     *         in="query",
     *         description="Whether to force delete the classroom and its related records",
     *         required=false,
     *         @OA\Schema(type="boolean", default=false)
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Classroom deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Classroom not found"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Cannot delete classroom with attached students"
     *     )
     * )
     */
    public function destroy(Classroom $classroom)
    {
        $classroom->loadMissing('students');
        if (($classroom->students()->count() == 0) || request()->boolean('forceDelete')) {
            $classroom->students()->delete();
            return $classroom->delete();
        } else {
            return response()->json(['msg' => 'Cannot delete this classroom because it has some students attached', 'data' => new ClassroomResource($classroom)], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
