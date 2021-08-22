<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('guest');
    }
    public function index()
    {
        return view('auth.register');
    }
    public function register(Request $request)
    {
        $this->validate($request, ['name' => 'required|max:32', 'email' => 'required|email|max:128', 'password' => 'required|confirmed']);
        $api = Str::random(16);
        $api_pass = Str::random(32);
        User::create(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password), 'api' => $api, 'api_pass' => $api_pass]);
        Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        return redirect()->route('dashboard');
    }
}
