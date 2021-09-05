<?php

namespace App\Http\Controllers\Api;

use App\Models\Api;
use App\Models\User;
use App\Models\Server;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Custom\Functions\PterodactylFunctions;

class PterodactylServerController extends Controller
{
    //
    private function returnApiInstance(Server $server)
    {
        $api_instance = Api::find(['id' => $server->api_id])->first();
        return $api_instance;
    }
    private function returnType(Api $api_instance)
    {
        $type = $api_instance->type;
        return $type;
    }
    public function information(Request $request, $server_id)
    {
        if (empty($request->bearerToken())) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $user = User::where('api_token', $request->bearerToken())->first();
        $server = $user->server()->where(['server_id' => $server_id])->firstOrFail();
        $api_instance = $this->returnApiInstance($server);
        $type = $this->returnType($api_instance);

        // 1 = Pterodactyl
        if ($type == 1) {
            $information = PterodactylFunctions::getPterodactylInformation($server, $api_instance);
            return response()->json($information);
        } else {
            return response()->json(["message" => "Wrong Server Type"], 404);
        }
    }
    public function resources(Request $request, $server_id)
    {
        if (empty($request->bearerToken())) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $user = User::where('api_token', $request->bearerToken())->first();
        $server = $user->server()->where(['server_id' => $server_id])->firstOrFail();
        $api_instance = $this->returnApiInstance($server);
        $type = $this->returnType($api_instance);

        // 1 = Pterodactyl
        if ($type == 1) {
            $resources = PterodactylFunctions::getPterodactylResources($server, $api_instance);
            return response()->json($resources);
        } else {
            return response()->json(["message" => "Wrong Server Type"], 404);
        }
    }

    public function power(Request $request, $server_id)
    {
        if (empty($request->bearerToken())) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $user = User::where('api_token', $request->bearerToken())->first();
        $server = $user->server()->where(['server_id' => $server_id])->firstOrFail();
        $api_instance = $this->returnApiInstance($server);
        $type = $this->returnType($api_instance);
        $action = $request->action;
        if ($action != 'start' && $action != 'stop' && $action != 'restart' &&  $action != 'kill') {
            return response()->json(["message" => "Invalid method"], 404);
        }
        $action = array('action' => $request->action);
        // 1 = Pterodactyl
        if ($type == 1) {
            $server_id = $server->server_id;
            $host_ip = $api_instance->hostname;
            $key = $api_instance->api;

            $protocol = "";
            if ($api_instance->protocol == 0) {
                $protocol = 'http';
            } else {
                $protocol = 'https';
            }

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $protocol . "://" . $host_ip . '/api/client/servers/' . $server->server_id . '/power');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $action);

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
            return response()->noContent(201);
        } else {
            return response()->json(["message" => "Wrong Server Type"], 404);
        }
    }
}
