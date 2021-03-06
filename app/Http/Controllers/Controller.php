<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

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
                "result" => "Resource not found"
            ];

            return response ($content, 404);
        };

        // CALL TO API The Guardian

    }

}
