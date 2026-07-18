<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ServerController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the framework and assigned the "api" middleware
| group with an /api prefix.
|
*/

/* User Management */
Route::post('/user/login', [UserController::class, 'login']);
Route::post('/user/register', [UserController::class, 'register']);
Route::middleware('auth:api')->get('/user/subuser', [UserController::class, 'subuser_list']);
Route::middleware('auth:api')->get('/user/subuser/{id}', [UserController::class, 'subuser_information']);
Route::middleware('auth:api')->get('/user/subuser/{id}/destroy', [UserController::class, 'subuser_destroy']);
Route::get('/user/{api_token}', [UserController::class, 'check']);

/* Server Management */
Route::middleware('auth:api')->get('/server', [ServerController::class, 'index']);
Route::middleware('auth:api')->get('/server/all', [ServerController::class, 'all']);
Route::middleware('auth:api')->get('/server/{id}', [ServerController::class, 'information']);
Route::middleware('auth:api')->get('/server/{id}/destroy', [ServerController::class, 'destroy']);
Route::middleware('auth:api')->post('/server/add', [ServerController::class, 'store']);
Route::middleware('auth:api')->post('/server/{id}/power', [ServerController::class, 'power']);

/* Api Management */
Route::middleware('auth:api')->get('/api', [ApiController::class, 'index']);
Route::middleware('auth:api')->get('/api/all', [ApiController::class, 'all']);
Route::middleware('auth:api')->get('/api/{id}/destroy', [ApiController::class, 'destroy']);
Route::middleware('auth:api')->get('/api/{id}/servers', [ApiController::class, 'servers']);
Route::middleware('auth:api')->post('/api/add', [ApiController::class, 'store']);
