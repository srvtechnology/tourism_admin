<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Hotel\HotelController;
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

Route::get('/', function () {
    return view('welcome');
});
// Route::get('/abc',function(){
// 	return 'sayan';
// });



Route::get('/config-clear', function() {

    $status = Artisan::call('config:clear');
     dd(env('JWT_SECRET'));
    return '<h1>Configurations cleared</h1>';
});
//Clear cache:
Route::get('/cache-clear', function() {
    $status = Artisan::call('cache:clear');
    return '<h1>Cache cleared</h1>';
});

//Clear configuration cache:
Route::get('/config-cache', function() {
    $status = Artisan::call('config:cache');
    return '<h1>Configurations cache cleared</h1>';
});


//Clear route cache:
Route::get('/route-cache', function() {
    $status = Artisan::call('route:clear');
    return '<h1>route cache cleared</h1>';
});


//Clear view cache:
Route::get('/view-cache', function() {
    $status = Artisan::call('view:clear');
    return '<h1>view cache cleared</h1>';
});


//Clear event cache:
Route::get('/event-cache', function() {
    $status = Artisan::call('event:clear');
    return '<h1>event cache cleared</h1>';
});

