<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Custom\Functions\ApiFunctions;
use App\Custom\Functions\VirtualizorFunctions;
use App\Custom\Handlers\Virtualizor_Enduser_API;

class VirtualizorServerController extends Controller
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

        // 0 = Virtualizor
        if ($type == 0) {
            $v = VirtualizorFunctions::createVirtualizorClient($api_instance);
            $information = VirtualizorFunctions::getVirtualizorInformation($v, $server);
            return response()->json($information);
        } else {
            return response()->json(["message" => "Invalid type"], 404);
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
        // 0 = Virtualizor
        if ($type == 0) {
            $v = VirtualizorFunctions::createVirtualizorClient($api_instance);
            $response = VirtualizorFunctions::sendPowerAction($v, $server, $action);
            if (empty($response)) {
                return response()->json(["message" => "Invalid response from host"]);
            }
            $response = array('message' => $response);
            return response()->json($response);
        } else {
            return response()->json(["message" => "Invalid type"], 404);
        }
    }
}
