<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClasseAnneeController;
use App\Http\Controllers\PlanificationCourController;
use App\Http\Controllers\PlanificationCourParClasseController;
use App\Http\Controllers\PlanificationSessionController;
use App\Http\Controllers\UserController;
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

Route::apiResource('cours', PlanificationCourController::class);
Route::apiResource('cour/classes', PlanificationCourParClasseController::class);
Route::apiResource('classeAnnee', ClasseAnneeController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('sessions', PlanificationSessionController::class);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/prof/{moduleId}', [PlanificationCourController::class, 'getProf']);
Route::get('/classe/{id}', [PlanificationSessionController::class, 'courClasse']);
Route::post('/salleDispo', [PlanificationSessionController::class, 'salleDispo']);
Route::post('/profDispo/{profId}', [PlanificationSessionController::class, 'profDispo']);
Route::post('/sessionDispo/{id}', [PlanificationSessionController::class, 'sessionDispo']);
