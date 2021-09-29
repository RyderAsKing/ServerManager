<?php

namespace App\Custom\Functions;

use App\Models\Api;
use App\Models\User;
use App\Models\Server;

class ApiFunctions
{
    public static function returnApiInstance(Server $server)
    {
        $api_instance = Api::find(['id' => $server->api_id])->first();
        return $api_instance;
    }
    public static function returnType(Api $api_instance)
    {
        $type = $api_instance->type;
        return $type;
    }
    public static function returnUser($bearerToken)
    {
        $user = User::where('api_token', $bearerToken)->first();
        return $user;
    }
    public static function returnParent($bearerToken)
    {
        $user = User::where('api_token', $bearerToken)->first();
        $parent = User::where('id', $user->parent_id)->first();
        return $parent;
    }
    public static function isParent($bearerToken)
    {
        $user = User::where('api_token', $bearerToken)->first();
        if (isset($user->parent_id) && $user->parent_id != null) {
            return false;
        } else {
            return true;
        }
    }
    public static function canAccessServer(User $user, $id)
    {
        $accessible_servers = $user->servers;
        if (in_array($id, $accessible_servers)) {
            return true;
        } else {
            return false;
        }
    }
}
