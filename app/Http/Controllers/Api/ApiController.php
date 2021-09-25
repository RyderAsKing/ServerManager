<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Custom\Functions\ApiFunctions;
use Illuminate\Support\Facades\Validator;
use App\Custom\Functions\PterodactylFunctions;

class ApiController extends Controller
{
    //
    public function index(Request $request)
    {
        if (empty($request->bearerToken())) {
            return response()->json(['message' => 'Unauthorized']);
        }
        $user = ApiFunctions::returnUser($request->bearerToken());
        $apis = $user->api()->paginate(5);
        return response()->json($apis);
    }

    public function all(Request $request)
    {
        if (empty($request->bearerToken())) {
            return response()->json(['message' => 'Unauthorized']);
        }
        $user = ApiFunctions::returnUser($request->bearerToken());
        $apis = $user->api()->get();
        return response()->json($apis);
    }

    public function destroy(Request $request, $id)
    {
        if (empty($request->bearerToken())) {
            return response()->json(['status' => 401, 'error' => true, 'error_message' => 'Unauthorized']);
        }
        $user = ApiFunctions::returnUser($request->bearerToken());
        $user->server()->where('api_id', $id)->delete();
        $user->api()->where('id', $id)->delete();
        return response()->json(['status' => 200, 'message' => 'API deleted successfully']);
    }

    public function store(Request $request)
    {
        if (empty($request->bearerToken())) {
            return response()->json(['status' => 401, 'error' => true, 'error_message' => 'Unauthorized']);
        }
        $validator = Validator::make($request->all(), ['type' => 'required|integer', 'api' => 'required|min:16', 'name' => 'required|max:64', 'hostname' => 'required|max:32', 'protocol' => 'required|max:32']);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => true, 'validation_errors' => $validator->messages()]);
        }

        if ($request->type == "0") { // Virtualizor
            if (empty($request->api_pass)) {
                return response()->json(['status' => 400, 'error' => true, 'error_message' => 'The API pass is required for Virtualizor API type']);
            }
        }
        $user = ApiFunctions::returnUser($request->bearerToken());

        $user->api()->create(['type' => $request->type, 'api' => $request->api, 'api_pass' => $request->api_pass, 'nick' => $request->name, 'hostname' => $request->hostname, 'protocol' => $request->protocol]);
        return response()->json(['status' => 200, 'message' => 'API added successfully']);
    }

    public function servers(Request $request, $id)
    {
        if (empty($request->bearerToken())) {
            return response()->json(['status' => 401, 'error' => true, 'error_message' => 'Unauthorized']);
        }
        $user = ApiFunctions::returnUser($request->bearerToken());
        $api = $user->api()->findOrFail($id);

        if ($api->type == 0) {
            return response()->json(['status' => 200, 'message' => 'This has not been added yet.']);
        }
        if ($api->type == 1) {
            $pterodactyl_response = PterodactylFunctions::getPterodactyServers($api);
            $response = [];
            if (isset($pterodactyl_response['error']) && $pterodactyl_response['error'] == true) {
                return response()->json($pterodactyl_response);
            } else {
                $response = ['status' => 200];
                foreach ($pterodactyl_response['data'] as $server) {
                    $exists = false;
                    if ($user->server()->where(['server_id' => $server['attributes']['identifier']])->exists()) {
                        $exists = true;
                    }
                    $semi_array = ['identifier' => $server['attributes']['identifier'], 'name' => $server['attributes']['name'], 'uuid' => $server['attributes']['uuid'], 'imported' => $exists];
                    array_push($response, $semi_array);
                }
                return response()->json($response);
            }
        }
    }
}
