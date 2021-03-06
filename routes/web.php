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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route to controller calling The Guardian API
Route::get('/{endpoint}', 'Controller@callApi');

// Route to handle any URI format except the "/{endpoint}" format
Route::fallback(function(){
    return response ("ERROR handled by web.php / URI not valid, resource not found", 404);
});
