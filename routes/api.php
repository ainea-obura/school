<?php

use App\Http\Controllers\AssignController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Route::post('register', [AuthController::class, 'register'])->middleware('log.route');
Route::middleware('auth:api')->group(function () {
    Route::get('logout', [AuthController::class, 'logout']);
    // Route::get('user',[AuthController::class,'user']);
    Route::post('students/create', [StudentController::class, 'store']);
    Route::get('students/all', [StudentController::class, 'index']);
    Route::post('/students/search', [StudentController::class, 'search']);

    Route::post('stream/create', [StreamController::class, 'store']);
    Route::get('stream/all', [StreamController::class, 'index']);

    Route::post('subject/create', [SubjectController::class, 'store']);
    Route::get('subject/all', [SubjectController::class, 'index']);

    Route::post('books/create', [BookController::class, 'store']);
    Route::get('books/all', [BookController::class, 'index']);

    Route::post('assign/create', [AssignController::class, 'store']);
    Route::get('assign/all', [AssignController::class, 'index']);
    Route::get('assign/{id}', [AssignController::class, 'show']);

    Route::delete('/assignments/clear/{assign}', [AssignController::class, 'destroy']);

    Route::post('change-password', [AuthController::class, 'changePassword']);
});
// Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);
