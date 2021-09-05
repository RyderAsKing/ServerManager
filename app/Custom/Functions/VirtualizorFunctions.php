<?php

namespace App\Custom\Functions;

use App\Models\Api;
use App\Models\Server;
use App\Custom\Handlers\Virtualizor_Enduser_API;

class VirtualizorFunctions
{
    public static function createVirtualizorClient(Api $api_instance)
    {
        $protocol = $api_instance->protocol;
        if ($protocol == 0) {
            $protocol = 'http';
        } else {
            $protocol = 'https';
        }
        $host_ip  = $api_instance->hostname;
        $key = $api_instance->api;
        $key_pass = $api_instance->api_pass;
        return new Virtualizor_Enduser_API($protocol, $host_ip, $key, $key_pass);
    }
    public static function getVirtualizorInformation($v, Server $server)
    {
        $current_information = array();
        $serverinfo = $v->vpsinfo($server->server_id);
        if (empty($serverinfo)) {
            return $current_information;
        }

        $is_vnc_available = $serverinfo['info']['vps']['vnc'];
        $vncinfo = "";
        $vnc_ip = "";
        $vnc_port = "";
        $vnc_password = "";
        if ($is_vnc_available == 1) {
            $vncinfo = $v->vnc($server->server_id);
            $vnc_ip = $vncinfo['ip'];
            $vnc_port = $vncinfo['port'];
            $vnc_password = $vncinfo['password'];
        }
        $ipv4 = $server->ipv4;
        $hostname = $server->hostname;
        $bandwidth_used = $serverinfo['info']['bandwidth']['used'];
        $storage = $serverinfo['info']['vps']['space'];
        $cores = $serverinfo['info']['vps']['cores'];
        $os_name = $serverinfo['info']['vps']['os_name'];
        $status = $serverinfo['info']['status'];
        $current_information = array('ipv4' => $ipv4, 'hostname' => $hostname, 'bandwidth_used' => $bandwidth_used, 'storage' => $storage, 'cores' => $cores, 'os_name' => $os_name, 'type' => 0, 'is_vnc_available' => $is_vnc_available, 'vnc_ip' => $vnc_ip, 'vnc_port' => $vnc_port, 'vnc_password' => $vnc_password, 'status' => $status);
        return $current_information;
    }

    public static function sendPowerAction($v, Server $server, $action)
    {
        if ($action == 'start') {
            $output = $v->start($server->server_id);
        }
        if ($action == 'stop') {
            $output = $v->stop($server->server_id);
        }
        if ($action == 'restart') {
            $output = $v->restart($server->server_id);
        }
        if ($action == 'kill') {
            $output = $v->poweroff($server->server_id);
        }
        return $output;
    }
}
