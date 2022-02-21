<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SubjectTeacherController;
use App\Http\Controllers\ReportPeriodController;

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
        Route::post('/', [ReportPeriodController::class, 'store']);
        Route::get('/{id}', [ReportPeriodController::class, 'show']);
        Route::patch('/{id}', [ReportPeriodController::class, 'update']);
    });
});