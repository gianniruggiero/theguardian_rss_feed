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

        // VALIDATION Endpoint string
        if(!($endpoint=="search" || $endpoint=="editions" || $endpoint=="sections" || $endpoint=="tags")){
            $endpointIsValid = !preg_match('/[^a-z\-]/', $endpoint);
        } else {
            $endpointIsValid = false;
        }

        // NOT VALID Endpoint string format, return XML error message
        if (!$endpointIsValid){
            $error_data = [
                "status_code" => 404,
                "message" => "The requested resource could not be found."
            ];
            $error_xml = view('error_xml', compact('error_data'));
            return response ($error_xml, 404)->header('Content-Type', 'text/xml');
        };

        // VALID Endpoint string format, call the API
        $client = new Client(['base_uri' => env('API_THEGUARDIAN_ENDPOINT')]);
        $format_key ="json";

        // Check the GuzzleHttp RequestException to handle API error status code,
        // otherwise the status codes (except 200) are not intercepted and create a Laravel error
        try {
            $response = $client->get($endpoint.'?api-key='.env('API_THEGUARDIAN_KEY').'&format='.$format_key,
                // false value to avoid "cUrl error 60" during the call, due to the absence of local SSL certificate
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
