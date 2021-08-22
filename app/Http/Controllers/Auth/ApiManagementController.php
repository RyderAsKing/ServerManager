<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiManagementController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('guest');
    }
}
