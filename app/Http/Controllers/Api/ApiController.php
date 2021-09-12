<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Custom\Functions\ApiFunctions;

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
}
