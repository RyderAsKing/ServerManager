<?php

namespace App\Http\Controllers\Server;

use Virtualizor;
use App\Models\Api;
use App\Models\server;

include("Virtualizor.php");

use App\Http\Controllers\Controller;
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
            'server_id' => 'required',
            'api_id' => 'required',
        ]);

        // Get API details
        $api = Api::where(['id' => $request->api_id])->first();

        // Check if the API can be used by the user
        $this->authorize('use_api', $api);

        // Get API type

        // 0 = Virtualizor
        $type = $api->type;
        $host_ip  = $api->hostname;

        $key = $api->api;
        $key_pass = $api->api_pass;
        $protocol = "";
        if ($api->protocol == 0) {
            $protocol = 'http';
        } else {
            $protocol = 'https';
        }

        if ($type == 0) {
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
            return redirect()->route("dashboard.server.index")->with('message', 'Server has been added successfully');
        }

        // 1 = Pterodactyl
        if ($type == 1) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $protocol . "://" . $host_ip . '/api/client/servers/' . $request->server_id);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


            $headers = array();
            $headers[] = 'Accept: application/json';
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: Bearer ' . $key;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            $result = json_decode($result, true);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
            if (isset($result['errors'])) {
                if ($result['errors'][0]['status'] == 403) {
                    return back()->with('status', 'The API key and password are incorrect');
                } elseif ($result['errors'][0]['status'] == 404) {
                    return back()->with('status', 'The requested server was not found');
                }
                return back()->with('status', 'There was an error adding the server');
            }
            $hostname = $result['attributes']['name'];
            $ipv4 = $result['attributes']['sftp_details']['ip'];
            if (!Auth::user()->server()->create(['server_type' => 1, 'server_id' => $request->server_id, 'hostname' => $hostname, 'ipv4' => $ipv4, 'api_id' => $request->api_id])) {
                return back()->with('status', 'There was an error adding the server');
            }
            return redirect()->route("dashboard.server.index")->with('message', 'Server has been added successfully');
        }
    }
}
