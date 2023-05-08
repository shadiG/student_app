<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Degree;
use App\Http\Resources\V1\Degree\DegreeCollection;
use App\Http\Resources\V1\Degree\DegreeResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Degree\StoreDegreeRequest;
use App\Http\Requests\V1\Degree\UpdateDegreeRequest;
use App\Filters\V1\DegreeFilter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DegreeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/degrees",
     *     summary="List all degrees",
     *     tags={"Degrees"},
     *     @OA\Parameter(
     *         name="includeClassrooms",
     *         in="query",
     *         description="Whether to include the classrooms of each degree",
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="includeStudents",
     *         in="query",
     *         description="Whether to include the students in each classroom",
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="List of degrees",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/DegreeResource")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $filter = new DegreeFilter();
        $filterItems = $filter->transform($request);

        $degrees = Degree::where($filterItems);

        if (request()->boolean('includeClassrooms')) {
            $degrees->with('classrooms');
        }
        if (request()->boolean('includeStudents')) {
            $degrees->with('classrooms.students');
        }

        return new DegreeCollection($degrees->paginate()->appends($request->query()));
    }


    /**
     * @OA\Post(
     *     path="/api/v1/degrees",
     *     summary="Create a new degree",
     *     tags={"Degrees"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Degree data",
     *         @OA\JsonContent(
     *             required={"name", "max_year"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="max_year", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="The created degree",
     *         @OA\JsonContent(ref="#/components/schemas/DegreeResource")
     *     )
     * )
     */
    public function store(StoreDegreeRequest $request)
    {
        return new DegreeResource(Degree::create($request->all()));
    }

    /**
     * @OA\Get(
     *     path="api/v1/degrees/{degree}",
     *     summary="Retrieve a single degree",
     *     tags={"Degrees"},
     *     @OA\Parameter(
     *         name="degree",
     *         in="path",
     *         description="ID of the degree to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="includeClassrooms",
     *         in="query",
     *         description="Whether to include the classroom for the degree",
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="includeStudents",
     *         in="query",
     *         description="Whether to include the students in the classroom of the degree",
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="The requested degree",
     *         @OA\JsonContent(ref="#/components/schemas/DegreeResource")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Degree not found"
     *     )
     * )
     */
    public function show(Degree $degree)
    {
        if (request()->boolean('includeClassrooms')) {
            $degree->loadMissing('classrooms');
        }
        if (request()->query('includeStudents')) {
            $degree->loadMissing('classrooms.students');
        }
        return new DegreeResource($degree);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/degrees/{id}",
     *     summary="Update a degree with PUT request",
     *     tags={"Degrees"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the degree to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Degree data",
     *         @OA\JsonContent(
     *             required={"name", "max_year"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="max_year", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Degree updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/DegreeResource")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Degree not found"
     *     )
     * )
     *
     * @OA\Patch(
     *     path="/api/v1/degrees/{id}",
     *     summary="Update a degree with PATCH request",
     *     tags={"Degrees"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the degree to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Degree data",
     *         @OA\JsonContent(
     *             required={},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="max_year", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Degree updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/DegreeResource")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Degree not found"
     *     )
     * )
     */
    public function update(UpdateDegreeRequest $request, Degree $degree)
    {
        $degree->fill($request->all())->save();
        return new DegreeResource($degree);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/degrees/{id}",
     *     summary="Delete a degree",
     *     tags={"Degrees"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the degree to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="forceDelete",
     *         in="query",
     *         description="Whether to force delete the degree and its related records",
     *         required=false,
     *         @OA\Schema(type="boolean", default=false)
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Degree deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Degree not found"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Cannot delete degree with attached classrooms"
     *     )
     * )
     */
    public function destroy(Degree $degree)
    {
        $degree->loadMissing('classrooms');
        $degree->loadMissing('classrooms.students');
        if (($degree->classrooms()->count() == 0 && $degree->students()->count() == 0) || request()->boolean('forceDelete')) {
            $degree->students()->delete();
            $degree->classrooms()->delete();
            return $degree->delete();
        } else {
            return response()->json(['msg' => 'Cannot delete this degree because it has some classrooms and students attached', 'data' => new DegreeResource($degree)], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
