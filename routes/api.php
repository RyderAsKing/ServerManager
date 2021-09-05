<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

Route::middleware('auth:api')->get('/server', [ServerController::class, 'index']);
Route::middleware('auth:api')->get('/server/{id}', [ServerController::class, 'information']);

/* Pterodactyl API */

Route::middleware('auth:api')->get('/server/pterodactyl/{server_id}', [PterodactylServerController::class, 'information']);
Route::middleware('auth:api')->get('/server/pterodactyl/{server_id}/resources', [PterodactylServerController::class, 'resources']);
Route::middleware('auth:api')->post('/server/pterodactyl/{server_id}/power', [PterodactylServerController::class, 'power']);

/* Virtualizor API */
Route::middleware('auth:api')->get('/server/virtualizor/{server_id}', [VirtualizorServerController::class, 'information']);
Route::middleware('auth:api')->post('/server/virtualizor/{server_id}/power', [VirtualizorServerController::class, 'power']);
