<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiManagementController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $apis = Auth::user()->api()->latest()->with('user')->paginate(5);
        return view('dashboard.api.index', ['apis' => $apis]);
    }
    public function add()
    {
        return view('dashboard.api.add');
    }
    public function store(Request $request)
    {
        $this->validate($request, ['type' => 'required|integer', 'api' => 'required|min:16', 'api_pass' => 'required|min:16']);
        return redirect()->route("dashboard.api.index");
    }
}
