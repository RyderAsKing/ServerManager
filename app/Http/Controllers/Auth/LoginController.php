<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('guest');
    }
    public function index()
    {
        return view("auth.login");
    }

    public function login(Request $request)
    {
        $this->validate($request, ['email' => 'required', 'password' => 'required']);
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember_me)) {
            return back()->with('status', 'Invalid user or password provided');
        }
        return view('dashboard.index');
    }
}
