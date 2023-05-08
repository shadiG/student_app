<?php

use App\Http\Controllers\Api\V1\ClassroomController;
use App\Http\Controllers\Api\V1\StudentController;
use App\Http\Controllers\Api\V1\DegreeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// api/v1
Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function (){
    Route::apiResource('degrees', DegreeController::class);
    Route::apiResource('classrooms', ClassroomController::class);
    Route::apiResource('students', StudentController::class);
});
