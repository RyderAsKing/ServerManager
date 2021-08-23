<?php

namespace App\Http\Controllers;

use Virtualizor;
use App\Models\Api;
use App\Models\server;

include("Virtualizor.php");

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class ServerViewController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function show()
    {
        $servers = Auth::user()->server()->latest()->with('user')->paginate(5);
        return view("dashboard.server.index", ['servers' => $servers]);
    }

    public function add()
    {
        $apis = Auth::user()->api()->get();
        return view("dashboard.server.add", ["apis" => $apis]);
    }

    public function store(Request $request)
    {
        // Validating the form 
        $this->validate($request, [
            'server_id' => 'required|numeric',
            'api_id' => 'required',
        ]);

        // Get API details
        $api = Api::where(['id' => $request->api_id])->first();

        // Check if the API can be used by the user
        $this->authorize('use_api', $api);

        // Get API type

        // 0 = Virtualizor
        $type = $api->type;
        if ($type == 0) {
            $host_ip  = $api->hostname;

            $key = $api->api;
            $key_pass = $api->api_pass;
            if ($api->protocol == 0) {
                $protocol = 'http';
            } else {
                $protocol = 'https';
            }
            if (Auth::user()->server()->where(['server_id' => $request->server_id, 'api_id' => $request->api_id])->count() > 0) {
                return back()->with('status', 'This server already exists in our database');
            }
            $v = new Virtualizor\Virtualizor_Enduser_API($protocol, $host_ip, $key, $key_pass);
            $vid = $request->server_id;
            $serverinfo = $v->vpsinfo($vid);
            if (empty($serverinfo)) {
                return back()->with('status', 'The requested server was not found');
            }
            if ($serverinfo['uid'] == -1) {
                return back()->with('status', 'The API key and password are incorrect');
            }
            $hostname = $serverinfo['info']['hostname'];
            $ipv4 = $serverinfo['info']['ip'][0];

            if (!Auth::user()->server()->create(['server_type' => 0, 'server_id' => $request->server_id, 'hostname' => $hostname, 'ipv4' => $ipv4, 'api_id' => $request->api_id])) {
                return back()->with('status', 'There was an error adding the server');
            }
            return redirect()->route("dashboard.server.index");
        }
    }
}
