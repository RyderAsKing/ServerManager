<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VpsActionController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function start()
    {
        return back();
    }

    public function stop()
    {
        return back();
    }

    public function restart()
    {
        return back();
    }

    public function destroy()
    {
        return back();
    }
}
