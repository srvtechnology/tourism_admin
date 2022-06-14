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
use App\Http\Controllers\Api\ActivityCategory\ActivityCategoryController;
use App\Http\Controllers\Api\ActivitySubCategory\ActivitySubCategoryController;
use App\Http\Controllers\Api\Activity\ActivityController;
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
    // Route::get('/admin/hotel/hotel-images-status/{id}',[HotelController::class, 'imagesStatusHotel']);


    // hotel-to-videos
      Route::post('/admin/hotel/hotel-videos-insert',[HotelController::class, 'videosAddHotel']);
      Route::get('/admin/hotel/hotel-videos-listing/{id}',[HotelController::class, 'videosListingHotel']);
      Route::get('/admin/hotel/hotel-videos-edit-view/{id}',[HotelController::class, 'videosEditViewHotel']);
      Route::post('/admin/hotel/hotel-videos-update',[HotelController::class, 'videosUpdateHotel']);
      Route::get('/admin/hotel/hotel-videos-delete/{id}',[HotelController::class, 'videosDeletegHotel']);
      // Route::get('/admin/hotel/hotel-videos-status/{id}',[HotelController::class, 'videosStatusHotel']);


      // rooms 
      Route::get('/admin/room/room-add-view',[RoomController::class, 'roomAddView']);
      Route::post('/admin/room/room-add',[RoomController::class, 'roomAdd']);
      Route::get('/admin/room/room-listing',[RoomController::class, 'roomListing']);
      Route::get('/admin/room/room-delete/{id}',[RoomController::class, 'roomDelete']);
      Route::get('/admin/room/room-edit/{id}',[RoomController::class, 'roomEdit']);
      Route::get('/admin/room/room-status/{id}',[RoomController::class, 'roomStatus']);
      Route::post('/admin/room/room-update',[RoomController::class, 'roomUpdate']);


      
      // rooms-amenities
      Route::get('/admin/room/amenities/{id}',[RoomController::class, 'roomAmenities']);
      Route::post('/admin/room/amenities/update',[RoomController::class, 'roomUpdateAmenities']);

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
      Route::post('/admin/airlines/update',[AirlineController::class, 'update']);

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
      Route::post('/admin/manage-route/update',[RouteController::class, 'update']);
      Route::get('/admin/manage-route/view/{id}',[RouteController::class, 'view']); 

      // tour-itinerary 
      Route::get('/admin/manage-tour-itinerary/addView',[TourItineraryController::class, 'addView']);
      Route::post('/admin/manage-tour-itinerary/add',[TourItineraryController::class, 'add']);
      Route::any('/admin/manage-tour-itinerary/listing',[TourItineraryController::class, 'listing']);
      Route::get('/admin/manage-tour-itinerary/status/{id}',[TourItineraryController::class, 'status']);
      Route::get('/admin/manage-tour-itinerary/delete/{id}',[TourItineraryController::class, 'delete']);
      Route::get('/admin/manage-tour-itinerary/edit/{id}',[TourItineraryController::class, 'edit']);
      Route::post('/admin/manage-tour-itinerary/update',[TourItineraryController::class, 'update']);
      Route::get('/admin/manage-tour-itinerary/view/{id}',[TourItineraryController::class, 'view']);


      // tour-meta-info
      Route::get('/admin/manage-tour-itinerary/meta-info/{id}',[TourItineraryController::class, 'metaInfoView']);
      Route::post('/admin/manage-tour-itinerary/meta-info/update',[TourItineraryController::class, 'metaInfoUpdate']);

      // tour-contact
      Route::get('/admin/manage-tour-itinerary/contact/{id}',[TourItineraryController::class, 'contactView']);
      Route::post('/admin/manage-tour-itinerary/contact/update',[TourItineraryController::class, 'contactUpdate']);

      // tour-policy
      Route::get('/admin/manage-tour-itinerary/policy/{id}',[TourItineraryController::class, 'policyView']);
      Route::post('/admin/manage-tour-itinerary/policy/update',[TourItineraryController::class, 'policyUpdate']);

      // tour-inclusion
      Route::get('/admin/manage-tour-itinerary/tour-inclusion/{id}',[TourItineraryController::class, 'inclusionView']);
      Route::post('/admin/manage-tour-itinerary/tour-inclusion/update',[TourItineraryController::class, 'inclusionUpdate']);

      // tour-exclusion
      Route::get('/admin/manage-tour-itinerary/tour-exclusion/{id}',[TourItineraryController::class, 'exclusionView']);
      Route::post('/admin/manage-tour-itinerary/tour-exclusion/update',[TourItineraryController::class, 'exclusionUpdate']);

      // tour-to-image
      Route::post('/admin/manage-tour-itinerary/images/add',[TourItineraryController::class, 'addImage']);
      Route::get('/admin/manage-tour-itinerary/images/listing/{id}',[TourItineraryController::class, 'ListingImage']);
      Route::get('/admin/manage-tour-itinerary/images/edit/{id}',[TourItineraryController::class, 'editImage']);
      Route::post('/admin/manage-tour-itinerary/images/update',[TourItineraryController::class, 'updateImage']);
      Route::get('/admin/manage-tour-itinerary/images/status/{id}',[TourItineraryController::class, 'statusImage']);
      Route::get('/admin/manage-tour-itinerary/images/delete/{id}',[TourItineraryController::class, 'deleteImage']);


      // tour-to-video 
      Route::post('/admin/manage-tour-itinerary/videos/add',[TourItineraryController::class, 'addVideo']);
      Route::get('/admin/manage-tour-itinerary/videos/listing/{id}',[TourItineraryController::class, 'listingVideo']);
      Route::get('/admin/manage-tour-itinerary/videos/status/{id}',[TourItineraryController::class, 'statusVideo']);
      Route::get('/admin/manage-tour-itinerary/videos/delete/{id}',[TourItineraryController::class, 'deleteVideo']);
      Route::get('/admin/manage-tour-itinerary/videos/edit/{id}',[TourItineraryController::class, 'editVideo']);
      Route::post('/admin/manage-tour-itinerary/videos/update',[TourItineraryController::class, 'updateVideo']);


      // tour-hotels
      Route::get('/admin/manage-tour-itinerary/hotel/listing/{id}',[TourItineraryController::class, 'hotelListing']);
      Route::post('/admin/manage-tour-itinerary/hotel/add',[TourItineraryController::class, 'hotelAdd']);
      Route::post('/admin/manage-tour-itinerary/hotel/suggestions',[TourItineraryController::class, 'hotelSuggesion']);

      // tour-flights
      Route::get('/admin/manage-tour-itinerary/flights/listing/{id}',[TourItineraryController::class, 'flightListing']);
      Route::post('/admin/manage-tour-itinerary/flights/update',[TourItineraryController::class, 'flightUpdate']);

      // tour-destination 
      Route::get('/admin/manage-tour-itinerary/details/{id}',[TourItineraryController::class, 'tourDetails']);
      Route::post('/admin/manage-tour-itinerary/details/update',[TourItineraryController::class, 'tourDetailsUpdate']);
      Route::get('/admin/manage-tour-itinerary/all-destination',[TourItineraryController::class, 'tourAllDestination']);


      // activity-category
      Route::post('/admin/activity-category/add',[ActivityCategoryController::class, 'add']); 
      Route::get('/admin/activity-category/listing',[ActivityCategoryController::class, 'listing']);
      Route::get('/admin/activity-category/delete/{id}',[ActivityCategoryController::class, 'delete']);  
      Route::get('/admin/activity-category/edit/{id}',[ActivityCategoryController::class, 'edit']);   
      Route::post('/admin/activity-category/update',[ActivityCategoryController::class, 'update']);

      // activity-sub-category
      Route::get('/admin/activity-sub-category/add-view',[ActivitySubCategoryController::class, 'addView']);
      Route::post('/admin/activity-sub-category/add',[ActivitySubCategoryController::class, 'add']); 
      Route::get('/admin/activity-sub-category/listing',[ActivitySubCategoryController::class, 'listing']);
      Route::get('/admin/activity-sub-category/delete/{id}',[ActivitySubCategoryController::class, 'delete']);
      Route::get('/admin/activity-sub-category/edit/{id}',[ActivitySubCategoryController::class, 'edit']);
      Route::post('/admin/activity-sub-category/update',[ActivitySubCategoryController::class, 'update']); 

      // activity
      Route::post('/admin/activity/get-sub-category',[ActivityController::class, 'getSubCategory']);
      Route::post('/admin/activity/on-change-region',[ActivityController::class, 'onChnageRegion']);
      Route::post('/admin/activity/on-change-dzongkhag',[ActivityController::class, 'onChnageDzongkhag']);
      Route::post('/admin/activity/on-change-dungkhag',[ActivityController::class, 'onChnageDungkhag']);
      Route::post('/admin/activity/on-change-gewog',[ActivityController::class, 'onChnageGewog']);

      Route::get('/admin/activity/add-view',[ActivityController::class, 'addView']);
      Route::post('/admin/activity/add',[ActivityController::class, 'add']);
      Route::any('/admin/activity/listing',[ActivityController::class, 'listing']);
      Route::get('/admin/activity/delete/{id}',[ActivityController::class, 'delete']);
      Route::get('/admin/activity/edit/{id}',[ActivityController::class, 'edit']);
      Route::post('/admin/activity/update',[ActivityController::class, 'update']);

      // activity-image
      Route::post('/admin/activity/image-upload',[ActivityController::class, 'imageUpload']);
      Route::get('/admin/activity/image-listing/{id}',[ActivityController::class, 'imageListing']);
      Route::get('/admin/activity/image-delete/{id}',[ActivityController::class, 'imageDelete']);
});