<?php

namespace App\Custom\Functions;

use App\Models\Api;
use App\Models\Server;

class PterodactylFunctions
{
    public static function getPterodactylResources(Server $server, Api $api_instance)
    {
        $host_ip = $api_instance->hostname;
        $key = $api_instance->api;

        $protocol = "";
        if ($api_instance->protocol == 0) {
            $protocol = 'http';
        } else {
            $protocol = 'https';
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $protocol . "://" . $host_ip . '/api/client/servers/' . $server->server_id . '/resources');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $key;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        $result = json_decode($result, true);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        if (isset($result['errors'])) {
            if ($result['errors'][0]['status'] == 403) {
                return array(['status' => 'The API key and password are incorrect']);
            } elseif ($result['errors'][0]['status'] == 404) {
                return array(['status' => 'The requested server was not found']);
            }
            return array(['status' => 'An unkown error occurred']);
        }
        $status = $result['attributes']['current_state'];
        $ram_current = round($result['attributes']['resources']['memory_bytes'] / 1024 / 1024, 0);
        $cpu_current = $result['attributes']['resources']['cpu_absolute'];
        $disk_current = round($result['attributes']['resources']['disk_bytes'] / 1024 / 1024, 0);
        $api_token = $server->user->api_token;
        $resources = array('status' => $status, 'memory_current' => $ram_current, 'cpu_current' => $cpu_current, 'disk_current' => $disk_current, 'memory_current_bytes' => $result['attributes']['resources']['memory_bytes'], 'disk_current_bytes' => $result['attributes']['resources']['disk_bytes'], 'api_token' => $api_token);
        return $resources;
    }
    public static function getPterodactylInformation(Server $server, Api $api_instance)
    {
        $server_id = $server->server_id;
        $host_ip = $api_instance->hostname;
        $key = $api_instance->api;

        $protocol = "";
        if ($api_instance->protocol == 0) {
            $protocol = 'http';
        } else {
            $protocol = 'https';
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $protocol . "://" . $host_ip . '/api/client/servers/' . $server->server_id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $key;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        $result = json_decode($result, true);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        if (isset($result['errors'])) {
            if ($result['errors'][0]['status'] == 403) {
                return array(['status' => 'The API key and password are incorrect']);
            } elseif ($result['errors'][0]['status'] == 404) {
                return array(['status' => 'The requested server was not found']);
            }
            return array(['status' => 'An unkown error occurred']);
        }
        $hostname = $result['attributes']['name'];
        $ipv4 = $result['attributes']['sftp_details']['ip'];
        $sftp_port = $result['attributes']['sftp_details']['port'];
        $disk = $result['attributes']['limits']['disk'];
        $cpu = $result['attributes']['limits']['cpu'];
        $memory = $result['attributes']['limits']['memory'];
        $uuid = $result['attributes']['uuid'];
        //$current_information = array('ipv4' => $ipv4, 'hostname' => $hostname, 'sftp_port' => $sftp_port, 'disk' => $disk, 'cpu' => $cpu, 'memory' => $memory, 'uuid' => $uuid, 'api_token' => $server->user->api_token);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $protocol . "://" . $host_ip . '/api/client/servers/' . $server->server_id . '/websocket');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $key;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $result = json_decode($result, true);
        $token = $result['data']['token'];
        $socket = $result['data']['socket'];
        $current_information = array('ipv4' => $ipv4, 'hostname' => $hostname, 'sftp_port' => $sftp_port, 'disk' => $disk, 'cpu' => $cpu, 'memory' => $memory, 'uuid' => $uuid, 'api_token' => $server->user->api_token, 'token' => $token, 'socket' => $socket, 'origin' => $protocol . "://" . $host_ip);
        return $current_information;
    }

    public static function sendPowerAction(Server $server, Api $api_instance, $action)
    {
        $server_id = $server->server_id;
        $host_ip = $api_instance->hostname;
        $key = $api_instance->api;
        $protocol = "";
        if ($api_instance->protocol == 0) {
            $protocol = 'http';
        } else {
            $protocol = 'https';
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $protocol . "://" . $host_ip . '/api/client/servers/' . $server->server_id . '/power');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $action);

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $key;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        $result = json_decode($result, true);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return;
    }
}
