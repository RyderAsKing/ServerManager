<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Custom\Functions\ApiFunctions;
use Illuminate\Support\Facades\Validator;

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
}
