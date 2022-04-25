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
use App\Http\Controllers\GradebookController;
use App\Http\Controllers\PredicatLetterController;
use App\Http\Controllers\GradebookComponentController;
use App\Http\Controllers\ScorecardController;
use App\Http\Controllers\ScorecardComponentController;
use App\Http\Controllers\ReportbookController;
use App\Http\Controllers\StudentAbsenceController;
use App\Http\Controllers\AttitudeController;
use App\Http\Controllers\AttitudePredicateController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\StudentAttitudeController;

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
        Route::delete('/{id}', [SubjectController::class, 'destroy']);
    });
    Route::prefix('teachers')->group(function () {
        Route::get('/', [TeacherController::class, 'index']);
        Route::get('/show/{id}', [TeacherController::class, 'show']);
        Route::patch('/{id}', [TeacherController::class, 'update']);
        Route::get('/subjects', [TeacherController::class, 'withSubject']);
        Route::get('/statistics', [TeacherController::class, 'accountStatistics']);
        Route::post('/', [TeacherController::class, 'store']);
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
        Route::delete('/{id}', [ReportPeriodController::class, 'destroy']);
    });
    Route::prefix('courses')->group(function () {
        Route::get('/', [CourseController::class, 'index']);
        Route::get('/teacher/{id}', [CourseController::class, 'indexForTeacher']);
        Route::get('/curriculums', [CourseController::class, 'getCurriculums']);
        Route::get('/entry-years', [CourseController::class, 'entryYears']);
        Route::post('/', [CourseController::class, 'store']);
        Route::get('/{id}', [CourseController::class, 'show']);
        Route::patch('/{id}', [CourseController::class, 'update']);
        Route::delete('/{id}', [CourseController::class, 'destroy']);
    });
    Route::prefix('student-courses')->group(function () {
        Route::get('/', [StudentCourseController::class, 'index']);
        Route::post('/', [StudentCourseController::class, 'store']);
        Route::get('/{id}', [StudentCourseController::class, 'selectStudents']);
        Route::patch('/{id}', [StudentCourseController::class, 'update']);
        Route::delete('/{id}/student/{studentId}', [StudentCourseController::class, 'destroy']);
    });
    Route::prefix('batches')->group(function () {
        Route::get('/', [BatchController::class, 'index']);
        Route::post('/', [BatchController::class, 'store']);
        Route::get('/{id}', [BatchController::class, 'show']);
        Route::patch('/{id}', [BatchController::class, 'update']);
        Route::delete('/{id}', [BatchController::class, 'destroy']);
    });
    Route::prefix('student-groups')->group(function () {
        Route::get('/', [StudentGroupController::class, 'index']);
        Route::get('/all', [StudentGroupController::class, 'getAll']);
        Route::post('/', [StudentGroupController::class, 'store']);
        Route::get('/{id}', [StudentGroupController::class, 'show']);
        Route::get('/by-course/{id}', [StudentGroupController::class, 'getByCourse']);
        Route::patch('/{id}', [StudentGroupController::class, 'update']);
        Route::delete('/{id}', [StudentGroupController::class, 'destroy']);
    });
    Route::prefix('students')->group(function () {
        Route::get('/', [StudentController::class, 'index']);
        Route::get('/student-group/{id}/absence', [StudentController::class, 'withAbsence']);
        Route::get('/absence/region/{region}', [StudentController::class, 'studentRegionwithAbsence']);
        Route::get('/prev-next/{id}', [StudentController::class, 'showWithNextPrev']);
        Route::get('/with-student-groups', [StudentController::class, 'getWithStudentGroup']);
        Route::post('/', [StudentController::class, 'store']);
        Route::get('/{id}', [StudentController::class, 'show']);
        Route::patch('/{id}', [StudentController::class, 'update']);
        Route::delete('/{id}', [StudentController::class, 'destroy']);
        Route::get('/region/get', [StudentController::class, 'getByRegion']);
        Route::get('/regions/check/not-signed', [StudentController::class, 'getNotSignedStudent']);
    });
    Route::prefix('majors')->group(function () {
        Route::get('/', [MajorController::class, 'index']);
    });
    Route::prefix('gradebooks')->group(function () {
        Route::get('/check-by-period-course', [GradebookController::class, 'checkPeriodCourse']);
        Route::get('/check-gradebook', [GradebookController::class, 'checkGradebook']);
        Route::get('/{id}', [GradebookController::class, 'show']);
        Route::post('/', [GradebookController::class, 'store']);
        Route::patch('/{id}', [GradebookController::class, 'update']);
    });
    Route::prefix('predicate-letters')->group(function () {
        Route::get('/{id}', [PredicatLetterController::class, 'index']);
        Route::get('/show/{id}', [PredicatLetterController::class, 'show']);
        Route::post('/', [PredicatLetterController::class, 'store']);
        Route::patch('/{id}', [PredicatLetterController::class, 'update']);
        Route::delete('/delete/{id}', [PredicatLetterController::class, 'destroy']);
    });
    Route::prefix('gradebook-components')->group(function () {
        Route::get('/gradebook/{id}', [GradebookComponentController::class, 'index']);
        Route::get('/{id}', [GradebookComponentController::class, 'show']);
        Route::post('/', [GradebookComponentController::class, 'store']);
        Route::patch('/{id}', [GradebookComponentController::class, 'update']);
        Route::delete('/delete/{id}', [GradebookComponentController::class, 'destroy']);
    });
    Route::prefix('scorecards')->group(function () {
        Route::get('/gradebook', [ScorecardController::class, 'index']);
        Route::get('/{id}', [ScorecardController::class, 'show']);
    });
    Route::prefix('scorecard-components')->group(function () {
        Route::get('/{id}', [ScorecardComponentController::class, 'show']);
        Route::patch('/{id}', [ScorecardComponentController::class, 'update']);
    });
    Route::prefix('reportbooks')->group(function () {
        Route::get('/report-period/{id}', [ReportbookController::class, 'checkByPeriod']);
        Route::get('/student/{id}', [ReportbookController::class, 'checkByStudent']);
        Route::get('/', [ReportbookController::class, 'index']);
        Route::post('/', [ReportbookController::class, 'store']);
        Route::post('/student', [ReportbookController::class, 'storeStudent']);
        Route::patch('/{id}', [ReportbookController::class, 'update']);
        Route::patch('/note/{id}', [ReportbookController::class, 'updateNote']);
        Route::get('/print', [ReportbookController::class, 'print']);
        Route::get('/attitude/print', [ReportbookController::class, 'printReportAttitude']);
    });
    Route::prefix('student-absences')->group(function () {
        Route::get('/check-get', [StudentAbsenceController::class, 'checkAndGet']);
        Route::post('/', [StudentAbsenceController::class, 'store']);
        Route::patch('/{id}', [StudentAbsenceController::class, 'update']);
    });
    Route::prefix('attitudes')->group(function () {
        Route::get('/', [AttitudeController::class, 'index']);
        Route::get('/types', [AttitudeController::class, 'types']);
        Route::post('/', [AttitudeController::class, 'store']);
        Route::get('/{id}', [AttitudeController::class, 'show']);
        Route::patch('/{id}', [AttitudeController::class, 'update']);
        Route::patch('/order/{id}', [AttitudeController::class, 'editOrder']);
        Route::delete('/{periodId}/attitude/{id}', [AttitudeController::class, 'destroy']);
    });
    Route::prefix('attitude-predicates')->group(function () {
        Route::post('/', [AttitudePredicateController::class, 'store']);
        Route::get('/{id}', [AttitudePredicateController::class, 'show']);
        Route::patch('/{id}', [AttitudePredicateController::class, 'update']);
        Route::delete('/{reportPeriodId}/predicate/{id}', [AttitudePredicateController::class, 'destroy']);
    });
    Route::prefix('regions')->group(function () {
        Route::get('/', [RegionController::class, 'index']);
        Route::get('/pluck-teacher', [RegionController::class, 'onlyGetTeacher']);
        Route::get('/check-teacher/{teacher}', [RegionController::class, 'checkTeacher']);
        Route::post('/', [RegionController::class, 'store']);
        Route::get('/{id}', [RegionController::class, 'show']);
        Route::patch('/{id}', [RegionController::class, 'update']);
    });
    Route::prefix('student-attitudes')->group(function () {
        Route::get('/{student}', [StudentAttitudeController::class, 'show']);
        Route::get('/edit/get-id', [StudentAttitudeController::class, 'edit']);
        Route::post('/', [StudentAttitudeController::class, 'store']);
        Route::patch('/{id}', [StudentAttitudeController::class, 'update']);
    });
});
