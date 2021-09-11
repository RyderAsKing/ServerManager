<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ServerController;
use App\Http\Controllers\Api\PterodactylServerController;
use App\Http\Controllers\Api\VirtualizorServerController;

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

/* Global API */

/* User Management */

Route::post('/user/login', [UserController::class, 'login']);
Route::post('/user/register', [UserController::class, 'register']);
Route::get('/user/{api_token}', [UserController::class, 'check']);

/* Server Management */
Route::middleware('auth:api')->get('/server', [ServerController::class, 'index']);
Route::middleware('auth:api')->get('/server/{id}', [ServerController::class, 'information']);
Route::middleware('auth:api')->post('/server/{id}/power', [ServerController::class, 'power']);
Route::middleware('auth:api')->post('/server/{id}/destroy', [ServerController::class, 'destroy']);
