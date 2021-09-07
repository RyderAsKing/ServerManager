<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    // Login in a account and then send back user array with the bearerToken
    public function login(Request $request)
    {
        $this->validate($request, ['email' => 'required', 'password' => 'required']);
        $user = User::where('email', $request->email)->firstOrFail();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $response = array('name' => $user->name, 'email' => $user->email, 'api_token' => $user->api_token);
                return response($response, 200);
            } else {
                $response = ["error" => true, "message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["error" => true, "message" => 'User does not exist'];
            return response($response, 422);
        }
    }

    // Register a new account
    public function register(Request $request)
    {
        $this->validate($request, ['name' => 'required|max:32', 'email' => 'required|email|max:128', 'password' => 'required']);

        // Check if email already exists
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $response = ["error" => true, "message" => 'A user with that email already exists'];
            return response($response, 422);
        } else {
            $api_token = Str::random(32);

            User::create(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password), 'api_token' => $api_token]);
            $response = array('name' => $request->name, 'email' => $request->email, 'api_token' => $api_token);
            return response($response, 200);
        }
    }

    // Check the API key
    public function check($api_token)
    {
        if (empty($api_token)) {
            $response = ["error" => true, "message" => 'Invalid API token provided'];
            return response($response, 422);
        }
        $user = User::where('api_token', $api_token)->firstOrFail();

        if ($user) {
            $response = array('name' => $user->name, 'email' => $user->email, 'api_token' => $user->api_token);
            return response($response, 200);
        } else {
            $response = ["error" => true, "message" => 'The API token is invalid'];
            return response($response, 422);
        }
    }
}
