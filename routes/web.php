<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Server\ServerViewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Server\ServerActionController;
use App\Http\Controllers\Api\ApiManagementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('{any}', function () {
    return view('app');
})->where('any', '.*');

/*
Route::get('/', function () {
    return view('home');
})->name("home");

Route::get('/dashboard', [DashboardController::class, 'index'])->name("dashboard");

Route::get('/login', [LoginController::class, 'index'])->name("login");
Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', [RegisterController::class, 'index'])->name("register");
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/logout', [LogoutController::class, 'index'])->name("logout");

Route::get('/dashboard/server', [ServerViewController::class, 'show'])->name("dashboard.server.index");
Route::get('/dashboard/server/add', [ServerViewController::class, 'add'])->name("dashboard.server.add"); //view
Route::post('/dashboard/server/add', [ServerViewController::class, 'store']); //request


Route::get('/dashboard/server/{server}', [ServerActionController::class, 'index'])->name("dashboard.server.current.index");
Route::get('/dashboard/server/{server}/start', [ServerActionController::class, 'start'])->name("dashboard.server.current.start");
Route::get('/dashboard/server/{server}/stop', [ServerActionController::class, 'stop'])->name("dashboard.server.current.stop");
Route::get('/dashboard/server/{server}/restart', [ServerActionController::class, 'restart'])->name("dashboard.server.current.restart");
Route::get('/dashboard/server/{server}/kill', [ServerActionController::class, 'kill'])->name("dashboard.server.current.kill");
Route::get('/dashboard/server/{server}/destroy', [ServerActionController::class, 'destroy'])->name("dashboard.server.current.destroy");
Route::post('/dashboard/server/{server}/change/hostname', [ServerActionController::class, 'changeHostname'])->name("dashboard.server.current.changehostname");
Route::post('/dashboard/server/{server}/change/password', [ServerActionController::class, 'changePassword'])->name("dashboard.server.current.changepassword");


Route::get('/dashboard/api', [ApiManagementController::class, 'index'])->name("dashboard.api.index"); //list
Route::get('/dashboard/api/add', [ApiManagementController::class, 'add'])->name("dashboard.api.add"); //show a form
Route::post('/dashboard/api/add', [ApiManagementController::class, 'store']); //request (when form filled)
Route::get('/dashboard/api/{api}/destroy', [ApiManagementController::class, 'destroy'])->name("dashboard.api.destroy"); //particular api
*/