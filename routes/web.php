<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VpsViewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VpsActionController;
use App\Http\Controllers\ApiManagementController;
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

Route::get('/', function () {
    return view('home');
})->name("home");

Route::get('/dashboard', [DashboardController::class, 'index'])->name("dashboard");

Route::get('/login', [LoginController::class, 'index'])->name("login");
Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', [RegisterController::class, 'index'])->name("register");
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/logout', [LogoutController::class, 'index'])->name("logout");

Route::get('/dashboard/vps', [VpsViewController::class, 'show'])->name("dashboard.vps.show");
Route::get('/dashboard/vps/add', [VpsViewController::class, 'add'])->name("dashboard.vps.add"); //view
Route::post('/dashboard/vps/add', [VpsViewController::class, 'store']); //request


Route::get('/dashboard/vps/{vps}', [VpsActionController::class, 'index'])->name("dashboard.vps.current.index");
Route::get('/dashboard/vps/{vps}/start', [VpsActionController::class, 'start'])->name("dashboard.vps.current.start");
Route::get('/dashboard/vps/{vps}/stop', [VpsActionController::class, 'stop'])->name("dashboard.vps.current.stop");
Route::get('/dashboard/vps/{vps}/restart', [VpsActionController::class, 'restart'])->name("dashboard.vps.current.restart");
Route::get('/dashboard/vps/{vps}/destroy', [VpsActionController::class, 'destroy'])->name("dashboard.vps.current.destroy");

Route::get('/dashboard/api', [ApiManagementController::class, 'index'])->name("dashboard.api.index"); //list
Route::get('/dashboard/api/add', [ApiManagementController::class, 'add'])->name("dashboard.api.add"); //show a form
Route::post('/dashboard/api/add', [ApiManagementController::class, 'store']); //request (when form filled)
Route::get('/dashboard/api/{api}/destroy', [ApiManagementController::class, 'destroy'])->name("dashboard.api.destroy"); //particular api
