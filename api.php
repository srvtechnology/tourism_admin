<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Login\LoginController;
use App\Http\Controllers\Api\Hotel\HotelController;
use App\Http\Controllers\Api\Room\RoomController;
use App\Http\Controllers\Api\Airport\AirportController;
use App\Http\Controllers\Api\Airline\AirlineController;
use App\Http\Controllers\Api\Flight\FlightController;
use App\Http\Controllers\Api\Tour\TourCategoryController;
use App\Http\Controllers\Api\Route\RouteController;
use App\Http\Controllers\Api\TourItinerary\TourItineraryController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/admin/login', [LoginController::class, 'login']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('/admin/test', [LoginController::class, 'test']);
    Route::post('/admin/logout', [LoginController::class, 'logout']);

    // hotel-add-api
    Route::get('/admin/hotel/addview', [HotelController::class, 'addView']);
    Route::post('/admin/hotel/insert', [HotelController::class, 'insertHotel']);
    Route::get('/admin/hotel/listing', [HotelController::class, 'listingHotel']);
    Route::get('/admin/hotel/view/{id}', [HotelController::class, 'viewHotel']);
    Route::get('/admin/hotel/status/{id}', [HotelController::class, 'statusHotel']);
    Route::get('/admin/hotel/delete/{id}', [HotelController::class, 'deleteHotel']);
    Route::post('/admin/hotel/update', [HotelController::class, 'updateHotel']);

    // hotel-to-facilities 
    Route::get('/admin/hotel/facilities/{id}', [HotelController::class, 'facilitiesHotel']);
    Route::post('/admin/hotel/facilities/update', [HotelController::class, 'facilitiesUpdateHotel']);

    // hotel-meta-info 
    Route::get('/admin/hotel/meta-information/{id}',[HotelController::class, 'metaInformationHotel']);
    Route::post('/admin/hotel/meta-information/update',[HotelController::class, 'metaInformationUpdateHotel']);

    // hotel-policy 
    Route::get('/admin/hotel/hotel-policy/{id}',[HotelController::class, 'policyHotel']);
    Route::post('/admin/hotel/hotel-policy/update',[HotelController::class, 'policyUpdateHotel']);

    // hotel-contact
    Route::get('/admin/hotel/hotel-contact/{id}',[HotelController::class, 'contactHotel']);
    Route::post('/admin/hotel/hotel-contact/update',[HotelController::class, 'contactUpdateHotel']);

    // hotel-to-images
    Route::post('/admin/hotel/hotel-images-insert',[HotelController::class, 'imagesAddHotel']);
    Route::get('/admin/hotel/hotel-images-listing/{id}',[HotelController::class, 'imagesListingHotel']);
    Route::get('/admin/hotel/hotel-images-edit-view/{id}',[HotelController::class, 'imagesEditViewgHotel']);
    Route::post('/admin/hotel/hotel-images-update',[HotelController::class, 'imagesUpdateHotel']);
    Route::get('/admin/hotel/hotel-images-delete/{id}',[HotelController::class, 'imagesDeletegHotel']);


    // hotel-to-videos
      Route::post('/admin/hotel/hotel-videos-insert',[HotelController::class, 'videosAddHotel']);
      Route::get('/admin/hotel/hotel-videos-listing/{id}',[HotelController::class, 'videosListingHotel']);
      Route::get('/admin/hotel/hotel-videos-edit-view/{id}',[HotelController::class, 'videosEditViewHotel']);
      Route::post('/admin/hotel/hotel-videos-update',[HotelController::class, 'videosUpdateHotel']);
      Route::get('/admin/hotel/hotel-videos-delete/{id}',[HotelController::class, 'videosDeletegHotel']);


      // rooms 
      Route::post('/admin/room/room-add',[RoomController::class, 'roomAdd']);
      Route::get('/admin/room/room-listing',[RoomController::class, 'roomListing']);
      Route::get('/admin/room/room-delete/{id}',[RoomController::class, 'roomDelete']);
      Route::get('/admin/room/room-edit/{id}',[RoomController::class, 'roomEdit']);
      Route::get('/admin/room/room-status/{id}',[RoomController::class, 'roomStatus']);
      Route::post('/admin/room/room-update',[RoomController::class, 'roomUpdate']);


      
      // rooms-amenities
      Route::get('/admin/room/amenities/{id}',[RoomController::class, 'roomAmenities']);
      Route::get('/admin/room/amenities/update',[RoomController::class, 'roomUpdateAmenities']);

      // room-extras 
      Route::post('/admin/room/extras/add',[RoomController::class, 'roomExtrasAdd']);
      Route::get('/admin/room/extras/listing/{id}',[RoomController::class, 'roomExtrasLisiting']);
      Route::get('/admin/room/extras/delete/{id}',[RoomController::class, 'roomExtrasDelete']);
      Route::get('/admin/room/extras/status/{id}',[RoomController::class, 'roomExtrasStatus']);
      Route::get('/admin/room/extras/edit/{id}',[RoomController::class, 'roomExtrasEdit']);
      Route::post('/admin/room/extras/update',[RoomController::class, 'roomExtrasUpdate']);

      // airports 
      Route::post('/admin/airports/add',[AirportController::class, 'add']);
      Route::get('/admin/airports/listing',[AirportController::class, 'listing']);
      Route::get('/admin/airports/status/{id}',[AirportController::class, 'status']);
      Route::get('/admin/airports/delete/{id}',[AirportController::class, 'delete']);
      Route::get('/admin/airports/edit/{id}',[AirportController::class, 'edit']);
      Route::post('/admin/airports/update',[AirportController::class, 'update']);

      // airlines 
      Route::post('/admin/airlines/add',[AirlineController::class, 'add']);
      Route::get('/admin/airlines/listing',[AirlineController::class, 'listing']);
      Route::get('/admin/airlines/status/{id}',[AirlineController::class, 'status']);
      Route::get('/admin/airlines/delete/{id}',[AirlineController::class, 'delete']);
      Route::get('/admin/airlines/edit/{id}',[AirlineController::class, 'edit']);
      Route::get('/admin/airlines/update',[AirlineController::class, 'update']);

      // featured-flights
      Route::post('/admin/featured-flights/add',[FlightController::class, 'add']);
      Route::get('/admin/featured-flights/listing',[FlightController::class, 'listing']);
      Route::get('/admin/featured-flights/delete/{id}',[FlightController::class, 'delete']);
      Route::get('/admin/featured-flights/status/{id}',[FlightController::class, 'status']);
      Route::get('/admin/featured-flights/edit/{id}',[FlightController::class, 'edit']);
      Route::post('/admin/featured-flights/update',[FlightController::class, 'update']);

      // tour-category
      Route::post('/admin/tour-category/add',[TourCategoryController::class, 'add']); 
      Route::get('/admin/tour-category/listing',[TourCategoryController::class, 'listing']);
      Route::get('/admin/tour-category/status/{id}',[TourCategoryController::class, 'status']); 
      Route::get('/admin/tour-category/delete/{id}',[TourCategoryController::class, 'delete']);  
      Route::get('/admin/tour-category/edit/{id}',[TourCategoryController::class, 'edit']);   
      Route::post('/admin/tour-category/update',[TourCategoryController::class, 'update']);  


      // routes 
      Route::get('/admin/manage-route/listing',[RouteController::class, 'listing']); 
      Route::get('/admin/manage-route/addView',[RouteController::class, 'addView']); 
      Route::post('/admin/manage-route/add',[RouteController::class, 'add']); 
      Route::get('/admin/manage-route/delete/{id}',[RouteController::class, 'delete']);
      Route::get('/admin/manage-route/status/{id}',[RouteController::class, 'status']);
      Route::get('/admin/manage-route/edit/{id}',[RouteController::class, 'edit']); 
      Route::get('/admin/manage-route/update',[RouteController::class, 'update']);
      Route::get('/admin/manage-route/view',[RouteController::class, 'view']); 

      // tour-itinerary 
      Route::get('/admin/manage-tour-itinerary/addView',[TourItineraryController::class, 'addView']);
      Route::post('/admin/manage-tour-itinerary/add',[TourItineraryController::class, 'add']);
      Route::get('/admin/manage-tour-itinerary/listing',[TourItineraryController::class, 'listing']);


});