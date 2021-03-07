<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route to controller calling The Guardian API
Route::get('/{endpoint}', 'Controller@callApi');

// Route to handle any URI format except the "/{endpoint}" format
Route::fallback(function(){
    $error_data = [
        "status_code" => 404,
        "message" => "The requested resource could not be found."
    ];
    $error_xml = view('error_xml', compact('error_data'));
    return response ($error_xml, 404)->header('Content-Type', 'text/xml');
});
