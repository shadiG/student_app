<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Student;
use App\Http\Requests\V1\Student\StoreStudentRequest;
use App\Http\Requests\V1\Student\UpdateStudentRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Student\StudentCollection;
use App\Http\Resources\V1\Student\StudentResource;
use Illuminate\Http\Request;
use App\Filters\V1\StudentFilter;


class StudentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/students",
     *     summary="Get a list of all students",
     *     tags={"Students"},
     *     @OA\Parameter(
     *         name="includeClassroom",
     *         in="query",
     *         description="Include the classroom relationship",
     *         required=false,
     *         @OA\Schema(
     *             type="boolean"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="includeDegree",
     *         in="query",
     *         description="Include the degree relationship",
     *         required=false,
     *         @OA\Schema(
     *             type="boolean"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/StudentResource")
     *         )
     *     ),
     * )
     */
    public function index(Request $request)
    {
        $filter = new StudentFilter();
        $filterItems = $filter->transform($request);

        $students = Student::where($filterItems);

        if (request()->boolean('includeClassroom')) {
            $students->with('classroom');
        }
        if (request()->boolean('includeDegree')) {
            $students->with('classroom.degree');
        }

        return new StudentCollection($students->paginate()->appends($request->query()));
    }


    /**
     * @OA\Post(
     *     path="/api/v1/students",
     *     summary="Create a new student",
     *     tags={"Students"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"classroom_id", "first_name", "last_name", "email", "gender"},
     *             @OA\Property(
     *                 property="classroom_id",
     *                 type="integer",
     *                 description="The ID of the classroom the student belongs to",
     *                 example="1"
     *             ),
     *             @OA\Property(
     *                 property="first_name",
     *                 type="string",
     *                 description="The first name of the student",
     *                 example="John"
     *             ),
     *             @OA\Property(
     *                 property="last_name",
     *                 type="string",
     *                 description="The last name of the student",
     *                 example="Doe"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 description="The email address of the student",
     *                 example="john.doe@example.com"
     *             ),
     *             @OA\Property(
     *                 property="gender",
     *                 type="string",
     *                 description="The gender of the student",
     *                 enum={"male", "female"},
     *                 example="male"
     *             ),
     *             @OA\Property(
     *                 property="date_of_birth",
     *                 type="string",
     *                 format="date",
     *                 description="The date of birth of the student (YYYY-MM-DD)",
     *                 example="2004-12-31"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Student created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Student created successfully"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/StudentResource"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Unprocessable entity",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The given data was invalid"
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 ref="#/components/schemas/StoreStudentRequest"
     *             )
     *         )
     *     ),
     * )
     */
    public function store(StoreStudentRequest $request)
    {
        return new StudentResource(Student::create($request->all()));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/students/{id}",
     *      summary="Get a student by ID",
     *      description="Returns a single student resource",
     *      tags={"Students"},
     *      @OA\Parameter(
     *          name="id",
     *          description="ID of the student to return",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="includeClassroom",
     *          description="Include the classroom related to the student",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="boolean",
     *              default="false"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="includeDegree",
     *          description="Include the degree related to the classroom",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="boolean",
     *              default="false"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/StudentResource"
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Student not found"
     *      ),
     * )
     */
    public function show(Student $student)
    {
        if (request()->boolean('includeClassroom')) {
            $student->loadMissing('classroom');
        }
        if (request()->boolean('includeDegree')) {
            $student->loadMissing('classroom.degree');
        }
        return new StudentResource($student);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/students/{id}",
     *     summary="Update a student resource by ID using PUT",
     *     description="Update a student resource by ID using PUT",
     *     operationId="updateStudentByIdPut",
     *     tags={"Students"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the student to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Update data",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="classroom_id",
     *                 description="ID of the classroom to which the student belongs",
     *                 type="integer",
     *                 example=1
     *             ),
     *             @OA\Property(
     *                 property="first_name",
     *                 description="First name of the student",
     *                 type="string",
     *                 example="John"
     *             ),
     *             @OA\Property(
     *                 property="last_name",
     *                 description="Last name of the student",
     *                 type="string",
     *                 example="Doe"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 description="Email of the student",
     *                 type="string",
     *                 example="john.doe@example.com"
     *             ),
     *             @OA\Property(
     *                 property="gender",
     *                 description="Gender of the student (male or female)",
     *                 type="string",
     *                 enum={"male", "female"},
     *                 example="male"
     *             ),
     *             @OA\Property(
     *                 property="date_of_birth",
     *                 description="Date of birth of the student (YYYY-MM-DD)",
     *                 type="string",
     *                 format="date",
     *                 example="2000-01-01"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 description="Updated student resource",
     *                 ref="#/components/schemas/StudentResource"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Student not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 description="Error message",
     *                 type="string",
     *                 example="Student not found"
     *             )
     *         )
     *     ),
     * )
     *
     * @OA\Patch(
     *     path="/api/v1/students/{id}",
     *     summary="Update a student resource by ID using PATCH",
     *     description="Update a student resource by ID using PATCH",
     *     operationId="updateStudentByIdPatch",
     *     tags={"Students"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the student to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Updated student object",
     *          @OA\JsonContent(
     *             required={},
     *              @OA\Property(
     *                  property="classroom_id",
     *                  type="integer",
     *                  description="ID of the classroom the student belongs to"
     *              ),
     *              @OA\Property(
     *                  property="first_name",
     *                  type="string",
     *                  description="First name of the student"
     *              ),
     *              @OA\Property(
     *                  property="last_name",
     *                  type="string",
     *                  description="Last name of the student"
     *              ),
     *              @OA\Property(
     *                  property="email",
     *                  type="string",
     *                  description="Email of the student",
     *                  format="email",
     *                  example="johndoe@example.com",
     *              ),
     *              @OA\Property(
     *                  property="gender",
     *                  type="string",
     *                  description="Gender of the student",
     *                  enum={"male", "female"}
     *              ),
     *              @OA\Property(
     *                  property="date_of_birth",
     *                  type="string",
     *                  description="Date of birth of the student (YYYY-MM-DD)",
     *                  format="date",
     *                  example="2000-01-01"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Student updated successfully",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/StudentResource"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Student not found"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      )
     *   )
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $student->fill($request->all())->save();
        return new StudentResource($student);
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/students/{id}",
     *      operationId="deleteStudent",
     *      tags={"Students"},
     *      summary="Delete existing student",
     *      description="Deletes a single student based on the ID passed in the path",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID of student to delete",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation, no content returned"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Student not found"
     *      )
     * )
     */
    public function destroy(Student $student)
    {
        return $student->delete();
    }
}
