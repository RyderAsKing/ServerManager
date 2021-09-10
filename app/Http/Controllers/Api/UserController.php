<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    // Login in a account and then send back user array with the bearerToken
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), ['email' => 'required|email', 'password' => 'required']);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'validation_errors' => $validator->messages()]);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $response = array('status' => 200, 'name' => $user->name, 'email' => $user->email, 'api_token' => $user->api_token, 'message' => 'Logged in successfully');
                return response($response);
            } else {
                $response = ["error" => true, "error_message" => "Password mismatch"];
                return response($response);
            }
        } else {
            $response = ["error" => true, "error_message" => 'User does not exist'];
            return response($response);
        }
    }

    // Register a new account
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), ['name' => 'required|max:32', 'email' => 'required|email|max:128|unique:users,email', 'password' => 'required|min:6']);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'validation_errors' => $validator->messages()]);
        }

        $api_token = Str::random(32);

        User::create(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password), 'api_token' => $api_token]);
        $response = array('status' => 200, 'name' => $request->name, 'email' => $request->email, 'api_token' => $api_token, 'message' => "Registered successfully");
        return response($response);
    }

    // Check the API key
    public function check($api_token)
    {
        if (empty($api_token)) {
            $response = ["error" => true, "error_message" => 'Invalid API token provided'];
            return response($response);
        }
        $user = User::where('api_token', $api_token)->firstOrFail();

        if ($user) {
            $response = array('status' => 200, 'name' => $user->name, 'email' => $user->email, 'api_token' => $user->api_token);
            return response($response);
        } else {
            $response = ["error" => true, "error_message" => 'The API token is invalid'];
            return response($response);
        }
    }
}
