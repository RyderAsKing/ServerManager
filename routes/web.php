<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| SPA catch-all — serves the React frontend for any non-API route.
|
*/

Route::get('{any}', function () {
    return view('app');
})->where('any', '.*');
