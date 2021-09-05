<?php

namespace App\Custom\Functions;

use App\Models\Api;
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
}
