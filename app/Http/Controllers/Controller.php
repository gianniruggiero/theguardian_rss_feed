<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function callApi($endpoint) {

        $validEndpoint = !preg_match('/[^a-z\-]/', $endpoint);

        // NOT VALID Endpoint string format 
        if (!$validEndpoint){
            $content = [
                "status" => 404,
                "result" => "ERROR: Resource not found - handled by Controller@callApi"
            ];
            return response ($content, 404);
        };

        // CA CERTIFICATE / path to the cacert.pem file, SSL certificate
        // $cacertPath = base_path().'\cacert.pem';

        // VALID Endpoint string format
        // CALL The Guardian API / verify => "false" to avoid "cUrl error 60" during the call, due to the absence of local SSL certificate
        $client = new Client(['base_uri' => 'https://content.guardianapis.com/']);
        $api_key = "9d97b471-ee1c-473a-b293-7998a92c4182"; // The Guardian api-key
        $format_key ="json";

        // Check the GuzzleHttp RequestException to handle API error status code,
        // otherway codes different from 200 are not intercepted and create a Laravel error
        try {
            $response = $client->get($endpoint.'?api-key='.$api_key.'&format='.$format_key,
                ['verify' => false]);
            $apiStatusCode = $response->getStatusCode();            
        } catch (RequestException $e) {
            $apiStatusCode = $e->getResponse()->getStatusCode();
            $apiErrorPhrase = $e->getResponse()->getReasonPhrase();
        }

        // REPLY to the client
        if ($apiStatusCode == 200) {
            $response = $client->get($endpoint.'?api-key='.$api_key.'&format='.$format_key,
                ['verify' => false]); 
            $response_json = json_decode($response->getBody(), true);
            return response ($response_json, 200);
        } else {
            return response ("ERROR: ".$apiStatusCode." - ".$apiErrorPhrase, $apiStatusCode);
        }

        // Call to test view
        // return view('test', compact('response_json'));
    }

}
