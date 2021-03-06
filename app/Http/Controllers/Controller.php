<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use GuzzleHttp\Client;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function callApi($endpoint) {

        // Endpoint string validation
        $validEndpoint = !preg_match('/[^a-z\-]/', $endpoint);

        // NOT VALID Endpoint string 
        if (!$validEndpoint){
            $content = [
                "status" => 404,
                "result" => "ERROR: Resource not found - handled by Controller@callApi"
            ];
            return response ($content, 404);
        };

        // VALID end-point string

        // CA CERTIFICATE / path to the cacert.pem file, SSL certificate
        $cacertPath = base_path().'\cacert.pem';

        // CALL The Guardian APi
        $client = new Client(['base_uri' => 'https://content.guardianapis.com/']);
        $api_key = "9d97b471-ee1c-473a-b293-7998a92c4182"; // The Guardian API key
        $format_key ="json";
        $response = $client->get($endpoint.'?api-key='.$api_key.'&format='.$format_key,
            ['verify' => $cacertPath]
        );
        $response_json = json_decode($response->getBody(), true);

        // REPLY to the client
        // return view('test', compact('response_json'));
        return response ($response_json, 200);
    }

}
