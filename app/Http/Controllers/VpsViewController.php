<?php

namespace App\Http\Controllers;

use App\Models\Vps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

include("Virtualizor.php");

use Virtualizor;

use function PHPUnit\Framework\isEmpty;

class VpsViewController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function show()
    {
        $vpss = Auth::user()->vps()->latest()->with('user')->paginate(5);
        return view("dashboard.vps.index", ['vpss' => $vpss]);
    }

    public function add()
    {
        return view("dashboard.vps.add");
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'virtualizor_server_id' => 'required|numeric',
            'api' => 'required',
            'host' => 'required'
        ]);

        $host_ip  = 'vps.velocitynode.net';

        $key = Auth::user()->api;
        $key_pass = Auth::user()->api_pass;

        if (Auth::user()->vps()->where('virtualizor_server_id', $request->virtualizor_server_id)->count() > 0) {
            return back()->with('status', 'This server already exists in our database');
        }
        $v = new Virtualizor\Virtualizor_Enduser_API($host_ip, $key, $key_pass);
        $vid = $request->virtualizor_server_id;
        $vpsinfo = $v->vpsinfo($vid);
        if (empty($vpsinfo)) {
            return back()->with('status', 'The requested server was not found');
        }
        if ($vpsinfo['uid'] == -1) {
            return back()->with('status', 'The API key and password are incorrect');
        }
        $hostname = $vpsinfo['info']['hostname'];
        $ipv4 = $vpsinfo['info']['ip'][0];

        if (!Auth::user()->vps()->create(['server_type' => 0, 'virtualizor_server_id' => $request->virtualizor_server_id, 'hetzner_server_id' => 0, 'hostname' => $hostname, 'ipv4' => $ipv4])) {
            return back()->with('status', 'There was an error adding the server');
        }
        return redirect()->route("dashboard.vps.show");
    }
}
