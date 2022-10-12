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
use App\Http\Controllers\Api\ServiceProvider\ServiceProviderController;
use App\Http\Controllers\Api\Transport\TransportController;
use App\Http\Controllers\Api\CmsCategory\CmsCategoryController;
use App\Http\Controllers\Api\CmsSubCategory\CmsSubCategoryController;
use App\Http\Controllers\Api\CmsSubSubCategory\CmsSubSubCategoryController;
use App\Http\Controllers\Api\Cms\CmsController;
use App\Http\Controllers\Api\TopDestination\TopDestinationController;
use App\Http\Controllers\Api\Region\RegionController;
use App\Http\Controllers\Api\Dzongkhag\DzongkhagController;
use App\Http\Controllers\Api\Dunkhag\DunkhagController;
use App\Http\Controllers\Api\Gewog\GewogController;
use App\Http\Controllers\Api\Village\VillageController;
use App\Http\Controllers\Api\Theme\ThemeController;
use App\Http\Controllers\Api\Attraction\AttractionCategory;
use App\Http\Controllers\Api\Poi\PoiController;
use App\Http\Controllers\Api\Header\HeaderImage;
use App\Http\Controllers\Api\Event\EventCategoryControllroller;
use App\Http\Controllers\Api\Review\ReviewController;
use App\Http\Controllers\Api\Separate\SeparateController;
use App\Http\Controllers\Api\UserManagement\UserManagementController;

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

Route::post('get-token',[LoginController::class, 'token']);

Route::post('/admin/login', [LoginController::class, 'login'])->middleware('cors');
// activity/////////////////////////////////////////////////////
Route::any('/admin/activity/listing',[ActivityController::class, 'listing']);
Route::get('/admin/activity/image-listing/{id}',[ActivityController::class, 'imageListing']);
Route::get('/admin/activity/video-listing/{id}',[ActivityController::class, 'videoListing']);

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
      
      Route::get('/admin/activity/delete/{id}',[ActivityController::class, 'delete']);
      Route::get('/admin/activity/edit/{id}',[ActivityController::class, 'edit']);
      Route::post('/admin/activity/update',[ActivityController::class, 'update']);

      // activity-image
      Route::post('/admin/activity/image-upload',[ActivityController::class, 'imageUpload']);
      
      Route::get('/admin/activity/image-delete/{id}',[ActivityController::class, 'imageDelete']);

      // activity-video
      Route::post('/admin/activity/video-upload',[ActivityController::class, 'videoUpload']);
      
      Route::get('/admin/activity/video-delete/{id}',[ActivityController::class, 'videoDelete']);

      


      // service-provider
      Route::post('/admin/service-provider/add',[ServiceProviderController::class,'add']);
      Route::get('/admin/service-provider/listing',[ServiceProviderController::class,'listing']);
      Route::get('/admin/service-provider/delete/{id}',[ServiceProviderController::class,'delete']);
      Route::get('/admin/service-provider/status/{id}',[ServiceProviderController::class,'status']);
      Route::get('/admin/service-provider/edit/{id}',[ServiceProviderController::class,'edit']);
      Route::get('/admin/service-provider/update',[ServiceProviderController::class,'update']);

      // trasportation
      Route::get('/admin/transportation-add-view',[TransportController::class,'addView']);
      Route::post('/admin/transportation-add',[TransportController::class,'add']);
      Route::any('/admin/transportation-list',[TransportController::class,'list']);
      Route::get('/admin/transportation-delete/{id}',[TransportController::class,'delete']);
      Route::get('/admin/transportation-edit/{id}',[TransportController::class,'edit']);
      Route::post('/admin/transportation-update',[TransportController::class,'update']);

      // trasportation image
      Route::post('/admin/transportation/image-add',[TransportController::class,'imageAdd']);
      Route::get('/admin/transportation/image-listing/{id}',[TransportController::class,'imageListing']);
      Route::get('/admin/transportation/image-delete/{id}',[TransportController::class,'imageDelete']);

      // trasportation video
      Route::post('/admin/transportation/video-add',[TransportController::class,'videoAdd']);
      Route::get('/admin/transportation/video-listing/{id}',[TransportController::class,'videoListing']);
      Route::get('/admin/transportation/video-delete/{id}',[TransportController::class,'videoDelete']);

      // cms-category
      Route::post('/admin/cms-category/add',[CmsCategoryController::class, 'add']); 
      Route::get('/admin/cms-category/listing',[CmsCategoryController::class, 'listing']);
      Route::get('/admin/cms-category/delete/{id}',[CmsCategoryController::class, 'delete']);  
      Route::get('/admin/cms-category/edit/{id}',[CmsCategoryController::class, 'edit']);   
      Route::post('/admin/cms-category/update',[CmsCategoryController::class, 'update']);

      // cms-subcategory
      Route::get('/admin/cms-sub-category/add-view',[CmsSubCategoryController::class, 'addView']);
      Route::post('/admin/cms-sub-category/add',[CmsSubCategoryController::class, 'add']); 
      Route::get('/admin/cms-sub-category/listing',[CmsSubCategoryController::class, 'listing']);
      Route::get('/admin/cms-sub-category/delete/{id}',[CmsSubCategoryController::class, 'delete']);
      Route::get('/admin/cms-sub-category/edit/{id}',[CmsSubCategoryController::class, 'edit']);
      Route::post('/admin/cms-sub-category/update',[CmsSubCategoryController::class, 'update']);

      // cms-sub-sub-category
      Route::post('/admin/get-cms-sub-category',[CmsSubSubCategoryController::class,'getCmsSubCategory']);
      Route::get('/admin/cms-sub-sub-category/add-view',[CmsSubSubCategoryController::class, 'addView']);
      Route::post('/admin/cms-sub-sub-category/add',[CmsSubSubCategoryController::class, 'add']);
      Route::get('/admin/cms-sub-sub-category/listing',[CmsSubSubCategoryController::class, 'listing']);
      Route::get('/admin/cms-sub-sub-category/delete/{id}',[CmsSubSubCategoryController::class, 'delete']);
      Route::get('/admin/cms-sub-sub-category/edit/{id}',[CmsSubSubCategoryController::class, 'edit']);
      Route::post('/admin/cms-sub-sub-category/update',[CmsSubSubCategoryController::class, 'update']);

      // cms
      Route::post('/admin/get-cms-sub-sub-category',[CmsController::class,'getSubSubCategory']);
      Route::get('/admin/manage-cms/add-view',[CmsController::class,'addView']);
      Route::post('/admin/manage-cms/add',[CmsController::class,'add']);
      Route::get('/admin/manage-cms/list',[CmsController::class,'list']);
      Route::get('/admin/manage-cms/edit/{id}',[CmsController::class,'edit']);
      Route::post('/admin/manage-cms/update',[CmsController::class,'update']);
});

Route::get('activity-listing-front-end',[ActivityController::class, 'frontActivity']);
Route::get('activity-details-front-end/{id}',[ActivityController::class, 'frontActivityDetails']);

// landing-page
Route::get('landing-page-data-get',[CmsController::class,'dataGet']);
Route::post('landing-page-data-get/update',[CmsController::class,'dataUpdate']);


// top-destination
Route::post('top-destination-add',[TopDestinationController::class,'add']);
Route::get('top-destination-listing',[TopDestinationController::class,'listing']);
Route::get('top-destination-edit/{id}',[TopDestinationController::class,'edit']);
Route::post('top-destination-update',[TopDestinationController::class,'update']);
Route::get('top-destination-delete/{id}',[TopDestinationController::class,'delete']);





///////////////////////////////////////////////////// master/////////////////////////////////////////////////////

// regions
Route::post('regions-add',[RegionController::class,'add']);
Route::get('regions-listing',[RegionController::class,'listing']);
Route::get('regions/edit/{id}',[RegionController::class,'edit']);
Route::post('regions/update',[RegionController::class,'update']);
Route::get('regions/delete/{id}',[RegionController::class,'delete']);

// Dzongkhag
Route::get('dzongkhag-add-view',[DzongkhagController::class,'addView']);
Route::post('dzongkhag-add',[DzongkhagController::class,'add']);
Route::get('dzongkhag-listing',[DzongkhagController::class,'listing']);
Route::get('dzongkhag-edit/{id}',[DzongkhagController::class,'edit']);
Route::post('dzongkhag-update',[DzongkhagController::class,'update']);
Route::get('dzongkhag-delete/{id}',[DzongkhagController::class,'delete']);

// dunkhag
Route::get('dungkhag-add-view',[DunkhagController::class,'addView']);
Route::post('dungkhag-add',[DunkhagController::class,'add']);
Route::get('dungkhag-listing',[DunkhagController::class,'listing']);
Route::get('dungkhag-edit/{id}',[DunkhagController::class,'edit']);
Route::post('dungkhag-update',[DunkhagController::class,'update']);
Route::get('dungkhag-delete/{id}',[DunkhagController::class,'delete']);

// gewog
Route::get('gewog-add-view',[GewogController::class,'addView']);
Route::post('get-dungkhag-data',[GewogController::class,'getDunkhag']);
Route::post('gewog-add',[GewogController::class,'add']);
Route::get('gewog-listing',[GewogController::class,'listing']);
Route::get('gewog-edit/{id}',[GewogController::class,'edit']);
Route::get('gewog-delete/{id}',[GewogController::class,'delete']);
Route::post('gewog-update',[GewogController::class,'update']);

// village
Route::get('village-add-view',[VillageController::class,'addView']);
Route::post('get-gewog-data',[VillageController::class,'getGewog']);
Route::post('village-add',[VillageController::class,'add']);
Route::get('village-listing',[VillageController::class,'listing']);
Route::get('village-edit/{id}',[VillageController::class,'edit']);
Route::get('village-delete/{id}',[VillageController::class,'delete']);
Route::post('village-update',[VillageController::class,'update']);



// theme-category

Route::post('theme-add',[ThemeController::class,'add']);
Route::get('theme-listing',[ThemeController::class,'listing']);
Route::get('theme-edit/{id}',[ThemeController::class,'edit']);
Route::post('theme-update',[ThemeController::class,'update']);
Route::get('theme-delete/{id}',[ThemeController::class,'delete']);

// attraction-category
Route::post('attraction-category-add',[AttractionCategory::class,'add']);
Route::get('attraction-category-listing',[AttractionCategory::class,'listing']);
Route::get('attraction-category-edit/{id}',[AttractionCategory::class,'edit']);
Route::post('attraction-category-update',[AttractionCategory::class,'update']);
Route::get('attraction-category-delete/{id}',[AttractionCategory::class,'delete']);



////////////////////////////////////////////////// end-master/////////////////////////////////////////////////////

// poi
Route::get('poi-add-view',[PoiController::class,'addView']);
Route::post('get-dzongkhag-data',[PoiController::class,'getDzongkhag']);
Route::post('poi-add',[PoiController::class,'add']);
Route::any('poi-listing',[PoiController::class,'listing']);
Route::get('poi-edit/{id}',[PoiController::class,'edit']);
Route::get('poi-delete/{id}',[PoiController::class,'delete']);
Route::post('poi-update',[PoiController::class,'update']);
Route::get('top-destination-poi',[PoiController::class,'topDestination']);
Route::get('monument-poi',[PoiController::class,'monumentPoi']);

// poi-image
Route::post('poi-image-add',[PoiController::class,'imageAdd']);
Route::get('poi-image-listing/{id}',[PoiController::class,'imageListing']);
Route::get('poi-image-delete/{id}',[PoiController::class,'imageDelete']);

// poi-video
Route::post('poi-video-add',[PoiController::class,'videoAdd']);
Route::get('poi-video-listing/{id}',[PoiController::class,'videoListing']);
Route::get('poi-video-delete/{id}',[PoiController::class,'videoDelete']);

// poi-close-date
Route::post('poi-close-date',[PoiController::class,'dateAdd']);
Route::get('poi-close-date/listing/{id}',[PoiController::class,'dateListing']);
Route::get('poi-close-date/edit/{id}',[PoiController::class,'dateEdit']);
Route::get('poi-close-date/update',[PoiController::class,'dateUpdate']);
// header-image
Route::post('header-image-add',[HeaderImage::class,'add']);
Route::get('header-image-listing',[HeaderImage::class,'listing']);
Route::get('header-image-edit/{id}',[HeaderImage::class,'edit']);
Route::post('header-image-update',[HeaderImage::class,'update']);
Route::get('header-image-delete/{id}',[HeaderImage::class,'delete']);


    // event-category
    Route::resource('index', App\Http\Controllers\Api\Event\EventCategoryControllroller::class);
    Route::post('/category/{id}', [App\Http\Controllers\Api\Event\EventCategoryControllroller::class, 'update_2']);

    // events
    Route::resource('events', App\Http\Controllers\Api\Event\EventController::class);
    Route::post('/events-data/{id}', [App\Http\Controllers\Api\Event\EventController::class, 'update_2']);

    Route::post('/events-data-filter', [App\Http\Controllers\Api\Event\EventController::class, 'index_1']);
    Route::get('/events-data/months', [App\Http\Controllers\Api\Event\EventController::class, 'months']);
    Route::get('/events-data/years', [App\Http\Controllers\Api\Event\EventController::class, 'years']);
    
    Route::resource('daily-activities', App\Http\Controllers\Api\Event\DailyActivitiesController::class);
    Route::post('/daily-activities-data/{id}', [App\Http\Controllers\Api\Event\DailyActivitiesController::class, 'update_2']);
    
    Route::resource('event-images', App\Http\Controllers\Api\Event\EventImageController::class);
    Route::post('/event-image/{id}', [App\Http\Controllers\Api\Event\EventImageController::class, 'update_2']);
    
    Route::resource('event-videos', App\Http\Controllers\Api\Event\EventVideoController::class);
    Route::post('/event-video/{id}', [App\Http\Controllers\Api\Event\EventVideoController::class, 'update_2']);


    Route::resource('blog-categorys', App\Http\Controllers\BlogCategoryController::class);
    Route::post('/blog-category/{id}', [App\Http\Controllers\BlogCategoryController::class, 'update_2']);

    Route::resource('blog-posts', App\Http\Controllers\BlogPostController::class);
    Route::post('/blog-post/{id}', [App\Http\Controllers\BlogPostController::class, 'update_2']);


    // review
    Route::post('post-review',[ReviewController::class,'postReview']);



// activity-contact
      Route::get('/admin/activity/contact-listing/{id}',[ActivityController::class, 'contactListing']);
      Route::post('/admin/activity/contact-add',[ActivityController::class, 'contactAdd']);
      Route::get('/admin/activity/contact-edit/{id}',[ActivityController::class, 'contactEdit']);
      Route::post('/admin/activity/contact-update',[ActivityController::class, 'contactUpdate']);
      Route::get('/admin/activity/contact-delete/{id}',[ActivityController::class, 'contactDelete']);


 // front-end
Route::get('all-region',[RegionController::class,'allRegion']);
Route::get('region-details/{id}',[RegionController::class,'regionDetails']);   

Route::get('dzongkhag-details/{id}',[RegionController::class,'dzongkhagDetails']);

Route::get('frontend-blogs',[App\Http\Controllers\BlogPostController::class, 'showBlogs']);
Route::get('frontend-blogs/details/{id}',[App\Http\Controllers\BlogPostController::class, 'showBlogsDetails']);

Route::get('national-park',[PoiController::class,'nationalPark']);



// activity-frontend-apis
Route::get('activity-category-with-subcategory',[ActivityController::class, 'categoryFetch']);
Route::get('activity-fetch-category/{id}',[ActivityController::class, 'categoryActivityFetch']);
Route::get('activity-fetch-sub-category/{id}',[ActivityController::class, 'subCategoryActivityFetch']);


Route::get('activity-fetch-region',[ActivityController::class, 'regionActivityFetch']);
Route::get('activity-fetch-dzongkhag',[ActivityController::class, 'dzonkhagActivityFetch']);



// cms-frontend
Route::get('cms-category',[CmsController::class,'fetchCategory']);
Route::get('cms-inner-category/{id}',[CmsController::class,'fetchInnerCategory']);
Route::get('cms-fetch-via-category/{id}',[CmsController::class,'fetchCms']);


// separate
Route::post('hotel-category-add',[SeparateController::class,'categoryAdd']);
Route::post('hotel-category-update',[SeparateController::class,'categoryUpdate']);

Route::post('hotel-add',[SeparateController::class,'hotelAdd']);
Route::post('hotel-update',[SeparateController::class,'hotelUpdate']);

Route::post('tour-oparator-add',[SeparateController::class,'tourOparatorAdd']);
Route::post('tour-oparator-update',[SeparateController::class,'tourOparatorUpdate']);

Route::post('guide-add',[SeparateController::class,'guideAdd']);
Route::post('guide-update',[SeparateController::class,'guideUpdate']);


// user-management
Route::get('manage-user-add-view',[UserManagementController::class,'addView']);
Route::post('manage-user-add',[UserManagementController::class,'add']);
Route::get('manage-user-listing',[UserManagementController::class,'listing']);
Route::get('manage-user-edit/{id}',[UserManagementController::class,'edit']);
Route::post('manage-user-update',[UserManagementController::class,'update']);
Route::get('manage-user-delete/{id}',[UserManagementController::class,'delete']);
Route::get('manage-user-status/{id}',[UserManagementController::class,'status']);

Route::post('change-dzongkhag-get-data',[UserManagementController::class,'getPoi']);
Route::post('change-category-get-data',[UserManagementController::class,'getHotel']);
