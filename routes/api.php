<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SubjectTeacherController;
use App\Http\Controllers\ReportPeriodController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\StudentGroupController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentCourseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::prefix('subjects')->group(function () {
        Route::post('/', [SubjectController::class, 'store']);
        Route::get('/', [SubjectController::class, 'index']);
        Route::get('/all', [SubjectController::class, 'getAll']);
        Route::get('/courses', [SubjectController::class, 'searchByCourse']);
        Route::get('/{id}', [SubjectController::class, 'show']);
        Route::patch('/{id}', [SubjectController::class, 'update']);
    });
    Route::prefix('teachers')->group(function () {
        Route::get('/', [TeacherController::class, 'index']);
        Route::get('/statistics', [TeacherController::class, 'accountStatistics']);
    });
    Route::prefix('subject-teachers')->group(function () {
        Route::get('/', [SubjectTeacherController::class, 'index']);
        Route::patch('/{id}', [SubjectTeacherController::class, 'update']);
    });
    Route::prefix('report-periods')->group(function () {
        Route::get('/', [ReportPeriodController::class, 'index']);
        Route::get('/school-years', [ReportPeriodController::class, 'schoolYears']);
        Route::post('/', [ReportPeriodController::class, 'store']);
        Route::get('/{id}', [ReportPeriodController::class, 'show']);
        Route::patch('/{id}', [ReportPeriodController::class, 'update']);
    });
    Route::prefix('courses')->group(function () {
        Route::get('/', [CourseController::class, 'index']);
        Route::get('/curriculums', [CourseController::class, 'getCurriculums']);
        Route::get('/entry-years', [CourseController::class, 'entryYears']);
        Route::post('/', [CourseController::class, 'store']);
        Route::get('/{id}', [CourseController::class, 'show']);
        Route::patch('/{id}', [CourseController::class, 'update']);
    });
    Route::prefix('student-courses')->group(function () {
        Route::get('/', [StudentCourseController::class, 'index']);
        Route::post('/', [StudentCourseController::class, 'store']);
        Route::get('/{id}', [StudentCourseController::class, 'selectStudents']);
        Route::patch('/{id}', [StudentCourseController::class, 'update']);
    });
    Route::prefix('batches')->group(function () {
        Route::get('/', [BatchController::class, 'index']);
        Route::post('/', [BatchController::class, 'store']);
        Route::get('/{id}', [BatchController::class, 'show']);
        Route::patch('/{id}', [BatchController::class, 'update']);
    });
    Route::prefix('student-groups')->group(function () {
        Route::get('/', [StudentGroupController::class, 'index']);
        Route::post('/', [StudentGroupController::class, 'store']);
        Route::get('/{id}', [StudentGroupController::class, 'show']);
        Route::get('/by-course/{id}', [StudentGroupController::class, 'getByCourse']);
        Route::patch('/{id}', [StudentGroupController::class, 'update']);
    });
    Route::prefix('students')->group(function () {
        Route::get('/', [StudentController::class, 'index']);
        Route::post('/', [StudentController::class, 'store']);
        Route::get('/{id}', [StudentController::class, 'show']);
        Route::patch('/{id}', [StudentController::class, 'update']);
    });
    Route::prefix('majors')->group(function () {
        Route::get('/', [MajorController::class, 'index']);
    });
});
