<?php

namespace Binay\Zoom\Helper;

use GuzzleHttp\Client;
use \Firebase\JWT\JWT;

class ZoomHelper
{
    public static function create_meeting($meeting_topic,$start_time,$duration = 30)
    {
        $client = new Client([
            'base_uri' => 'https://api.zoom.us',
        ]);
        $access_token = (new self)->getZoomAccessToken();
        $response = $client->request('POST', '/v2/users/me/meetings', [
            "headers" => [
                "Authorization" => "Bearer " . $access_token
            ],
            'json' => [
                "topic" => $meeting_topic,
                "type" => 2,
                "start_time" => $start_time,
                "duration" => $duration,
                "password" => mt_rand(100000,999999)
            ],
        ]);
     
        $data = json_decode($response->getBody());
     
    }

    public static function view_meeting()
    {
        $client = new Client(['base_uri' => 'https://api.zoom.us']);
        
        $access_token = (new self)->getZoomAccessToken();

        $response = $client->request('GET', '/v2/users/me/meetings', [
            "headers" => [
                "Authorization" => "Bearer ". $access_token
            ]
        ]);
        
        $data = json_decode($response->getBody());
        
        if ( !empty($data) ) {
            foreach ( $data->meetings as $meeting ) {
                $meetings[] = [
                    'topic' => $meeting->topic,
                    'join_url' => $meeting->join_url,
                ];
            }
        }
    }

    public static function delete_meeting($meeting_id)
    {
        $client = new Client(['base_uri' => 'https://api.zoom.us']);
        
        $access_token = (new self)->getZoomAccessToken();

        $response = $client->request("DELETE", "/v2/meetings/$meeting_id", [
            "headers" => [
                "Authorization" => "Bearer " . $access_token
            ]
        ]);
     
        if (204 == $response->getStatusCode()) {
            echo "Meeting deleted.";
        }
    }

    private function getZoomAccessToken() {
        $key = config('zoom.APP_SECRET');
        $payload = array(
            "iss" => config('zoom.APP_ID'),
            'exp' => time() + 3600,
        );
        return JWT::encode($payload, $key);    
    }
}