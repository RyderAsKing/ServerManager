<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Custom\Functions\ApiFunctions;
use App\Custom\Functions\PterodactylFunctions;

class PterodactylServerController extends Controller
{
    //
    public function information(Request $request, $server_id)
    {
        if (empty($request->bearerToken())) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $user = ApiFunctions::returnUser($request->bearerToken());
        $server = $user->server()->where(['server_id' => $server_id])->firstOrFail();
        $api_instance = ApiFunctions::returnApiInstance($server);
        $type = ApiFunctions::returnType($api_instance);

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
        $user = ApiFunctions::returnUser($request->bearerToken());
        $server = $user->server()->where(['server_id' => $server_id])->firstOrFail();
        $api_instance = ApiFunctions::returnApiInstance($server);
        $type = ApiFunctions::returnType($api_instance);

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
        $user = ApiFunctions::returnUser($request->bearerToken());
        $server = $user->server()->where(['server_id' => $server_id])->firstOrFail();
        $api_instance = ApiFunctions::returnApiInstance($server);
        $type = ApiFunctions::returnType($api_instance);
        $action = $request->action;
        if ($action != 'start' && $action != 'stop' && $action != 'restart' &&  $action != 'kill') {
            return response()->json(["message" => "Invalid method"], 404);
        }
        // 1 = Pterodactyl
        if ($type == 1) {
            $action = ['signal' => $action];
            $action = json_encode($action);
            $response = PterodactylFunctions::sendPowerAction($server, $api_instance, $action);
            if (empty($response)) {
                $response = array('message' => 'Action successful');
            }
            return response()->json($response);
        } else {
            return response()->json(["message" => "Wrong Server Type"], 404);
        }
    }
}
