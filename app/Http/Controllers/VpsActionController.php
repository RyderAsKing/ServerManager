<?php

namespace App\Http\Controllers;

use App\Models\Vps;
use Illuminate\Http\Request;

class VpsActionController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Vps $vps)
    {
        $this->authorize("use", $vps);

        return view('dashboard.vps.current');
    }
    public function start(Vps $vps)
    {
        $this->authorize("use", $vps);

        return back();
    }

    public function stop(Vps $vps)
    {
        $this->authorize("use", $vps);

        return back();
    }

    public function restart(Vps $vps)
    {
        $this->authorize("use", $vps);

        return back();
    }

    public function destroy(Vps $vps)
    {
        $this->authorize("use", $vps);

        return back();
    }
}
