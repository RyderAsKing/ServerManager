<?php

use App\Http\Controllers\Api\PterodactylServerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/server/pterodactyl/{server_id}', [PterodactylServerController::class, 'information']);
Route::middleware('auth:api')->get('/server/pterodactyl/{server_id}/resources', [PterodactylServerController::class, 'resources']);
