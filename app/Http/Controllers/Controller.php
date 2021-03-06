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
            $error_data = [
                "status_code" => 404,
                "message" => "The requested resource could not be found."
            ];
            $error_xml = view('error_xml', compact('error_data'));
            return response ($error_xml, 404)->header('Content-Type', 'text/xml');
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
            $response_json = json_decode($response->getBody(), true);
            // Fill the RSS feed view-template with json data
            $rss_feed = view('rss_feed', compact('response_json'));
            // Reply to the client the RSS Feed / XML format
            return response($rss_feed, 200)->header('Content-Type', 'text/xml');
        } else {
            $error_data = [
                "status_code" => 404,
                "message" => "The requested resource could not be found."
            ];
            // Fill the RSS feed view-template with error data
            $error_xml = view('error_xml', compact('error_data'));
            return response ($error_xml, 404)->header('Content-Type', 'text/xml');
        }

    }

}
