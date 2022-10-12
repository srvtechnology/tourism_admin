<?php

namespace App\Http\Controllers\Api\Poi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\Poi;
use App\Models\ThemeCategory;
use App\Models\AttractionCategoryModel;
use App\Models\Dzongkhag;
use App\Models\Dungkhag;
use App\Models\Village;
use App\Models\Gewog;
use App\Models\PoiImages;
use App\Models\PoiVideo;
use App\Models\PoiCloseDate;
use App\Models\EventModel;
use App\Models\Review;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Response;
use Illuminate\Support\Facades\Http;
use Storage;
class PoiController extends Controller
{
    public function addView()
    {
        $response = [];
        try{
         $response['success'] = true;
         $response['regions'] = Region::get();
         $response['theme_category'] = ThemeCategory::get();
         $response['attraction_category'] = AttractionCategoryModel::get();
         return Response::json($response);
         }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }

    public function getDzongkhag(Request $request)
    {
        $response = [];
        
        try{
         $dzongkhag = Dzongkhag::where('region_id',$request->region_id)->get();
         $response['success'] = true;
         $response['dzongkhag'] = $dzongkhag;   
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }

    public function add(Request $request)
    {
        $response = [];
        
        try{

        $validator = Validator::make($request->all(), [
            'region' => 'required',
            'dzongkhag' => 'required',
            'destination_name' => 'required',

            'attraction_details' => 'required',
            'inbound_option' => 'required',
            

            
            'inbound_option' => 'required',
            

            'business_operating_hours' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',

            'destination_header_image' => 'required',
            'destination_teaser_image' => 'required',
        ]);

        //Send failed response if request is not valid
            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()], 200);
            }

            $ins_data=new Poi;
            $ins_data->region=$request->region;
            $ins_data->dzongkhag=$request->dzongkhag;
            $ins_data->dungkhag=$request->dungkhag;
            $ins_data->gewog=$request->gewog;
            $ins_data->village=$request->village;
            $ins_data->destination_name=$request->destination_name;
            $ins_data->attraction_category=$request->attraction_category;
            $ins_data->theme_category=$request->theme_category;
            $ins_data->attraction_details=$request->attraction_details;
            $ins_data->activity_details=$request->activity_details;
            $ins_data->inbound_option=$request->inbound_option;
            $ins_data->culture_advice=$request->culture_advice;
            $ins_data->public_holidays=$request->public_holidays;
            $ins_data->festivals_events=$request->festivals_events;
            $ins_data->business_operating_hours=$request->business_operating_hours;
            $ins_data->weather_seasonal_factors=$request->weather_seasonal_factors;
            $ins_data->amenities=$request->amenities;
            $ins_data->disable_friendly_services=$request->disable_friendly_services;
            $ins_data->theme_details=$request->theme_details;
            $ins_data->accomodation_details=$request->accomodation_details;
            $ins_data->additional_resources=$request->additional_resources;
            $ins_data->latitude=$request->latitude;
            $ins_data->longitude=$request->longitude;
            $ins_data->contact_person=$request->contact_person;
            $ins_data->phone_no=$request->phone_no;
            $ins_data->email=$request->email;
            $ins_data->mobile=$request->mobile;
            $ins_data->monument=$request->monument;
            $ins_data->maximum_capacity=$request->maximum_capacity;
            $ins_data->cost_of_visit=$request->cost_of_visit;
            $ins_data->guide_mandatory=$request->guide_mandatory;
            $ins_data->top_destination=$request->top_destination;
            
            if ($request->hasFile('destination_header_image'))
            {
             $destination_header_image = $request->destination_header_image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $destination_header_image->getClientOriginalExtension();
             $destination_header_image->move("storage/app/public/destination_header_image",$filename);
             $ins_data->destination_header_image = $filename;
           }

           if ($request->hasFile('destination_teaser_image'))
            {
             $destination_teaser_image = $request->destination_teaser_image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $destination_teaser_image->getClientOriginalExtension();
             $destination_teaser_image->move("storage/app/public/destination_teaser_image",$filename);
             $ins_data->destination_teaser_image = $filename;
           }


           if ($request->hasFile('destination_map'))
            {
             $destination_map = $request->destination_map;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $destination_map->getClientOriginalExtension();
             $destination_map->move("storage/app/public/destination_map",$filename);
             $ins_data->destination_map = $filename;
           }

           $ins_data->qr_code = $request->qr_code;
           
           if (@$request->qr_code=="1") {

            $name = 'Attraction Name : '.$request->destination_name;
            
            $additional = " Dos and Don't : ".$request->attraction_details;
            $contact = 'Contact Person : '.$request->contact_person;
            $mobile = 'Mobile : '.$request->phone_no;

            $data = 'Attraction Details :-     
                          
    '.$name.'

    '.$additional.'

    '.$contact.'

    '.$mobile.'';


                $request_one = Http::get('https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl='.$data.'');
                if ($request_one->ok()) {
                $filename = time() . '-' . rand(1000, 9999) . '.png';    
                Storage::put($filename, $request_one->body());
                $ins_data->qr_image = $filename;
                $response['qr_image_name'] = $filename;
                $response['qr_link'] = 'http://services.tourism.gov.bt/storage/app/';
                }

           }
            

           $ins_data->save();
           $response['success'] = true;
           $response['message'] = 'Destination Added Successfully';
           

           
           return $response;
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }    
    }


    public function listing(Request $request)
    {
        $response = [];
        try{
         $data = Poi::with('dzongkhag_name','dungkhag_name','gewog_name','region_name','village_name','theme_name','attraction_category_name');
         if (@$request->region) {
             $data = $data->where('region',$request->region);
         }
         if (@$request->dzongkhag) {
            $data = $data->where('dzongkhag',$request->dzongkhag);
         }
         if (@$request->gewog) {
            $data = $data->where('gewog',$request->gewog);
         }
         if (@$request->theme_category) {
            $data = $data->where('theme_category',$request->theme_category);
         }

         if (@$request->attraction_category) {
            $data = $data->where('attraction_category',$request->attraction_category);
         }

         $data = $data->get();

         $response['success'] = true;
         $response['data'] = $data;
         $response['destination_header_image'] = 'http://services.tourism.gov.bt/storage/app/public/destination_header_image/';
         $response['destination_teaser_image'] = 'http://services.tourism.gov.bt/storage/app/public/destination_teaser_image/'; 
         $response['destination_map'] = 'http://services.tourism.gov.bt/storage/app/public/destination_map/'; 
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function edit($id)
    {
        $response = [];
        try{
         $data = Poi::where('id',$id)->first();
         $response['success'] = true;
         $response['data'] = $data;
         $response['regions'] = Region::get();
         $response['theme_category'] = ThemeCategory::get();
         $response['attraction_category'] = AttractionCategoryModel::get();
         $response['dzongkhag'] = Dzongkhag::where('region_id',$data->region_id)->get();
         $response['dungkhag'] = Dungkhag::where('dzongkhag_id',$data->dzongkhag_id)->get();
         $response['gewog'] = Gewog::where('dzongkhag_id',$data->dzongkhag_id)->get();

         // review
         $response['reviews'] = Review::where('poi_id',$id)->get();
         $response['total_review_count'] = Review::where('poi_id',$id)->count();
         $response['avg_review'] = Review::where('poi_id',$id)->avg('rating');
         $response['one_star'] = Review::where('poi_id',$id)->where('rating',1)->count();
         $response['two_star'] = Review::where('poi_id',$id)->where('rating',2)->count();
         $response['three_star'] = Review::where('poi_id',$id)->where('rating',3)->count();
         $response['four_star'] = Review::where('poi_id',$id)->where('rating',4)->count();
         $response['five_star'] = Review::where('poi_id',$id)->where('rating',5)->count();

         $response['destination_header_image'] = 'http://services.tourism.gov.bt/storage/app/public/destination_header_image/';
         $response['destination_teaser_image'] = 'http://services.tourism.gov.bt/storage/app/public/destination_teaser_image/'; 
         $response['destination_map'] = 'http://services.tourism.gov.bt/storage/app/public/destination_map/'; 







         // multiple-image
         $response['images'] = PoiImages::where('poi_id',$id)->get();
         $response['image_link'] = 'http://services.tourism.gov.bt/storage/app/public/poi_image/'; 
         // multiple-video
         $response['videos'] = PoiVideo::where('poi_id',$id)->get();
         $response['video_link'] = 'http://services.tourism.gov.bt/storage/app/public/poi_video/';


         // surrounding
         $response['surrounding'] = Poi::where('dzongkhag',$data->dzongkhag)->get();


         // events
         $response['events']=EventModel::where('region_id',$data->region)->with('getRegionDetails','getGewogDetails','getDzongkhagDetails','getDungkhagDetails','getVillageDetails','getEventCategoryDetails')->get();
        
        $response['event_image_link'] = 'http://services.tourism.gov.bt/storage/app/public/event/';

        $response['qr_image_link'] = 'http://services.tourism.gov.bt/storage/app/';


         
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function delete($id)
    {
        $response = [];
        try{
         $check = Poi::where('id',$id)->delete();
         $response['success'] = true;
         $response['message'] = 'Poi deleted Successfully';   
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function update(Request $request)
    {
        $response = [];
        
        try{

        $validator = Validator::make($request->all(), [
            'region' => 'required',
            'dzongkhag' => 'required',
            'destination_name' => 'required',

            'attraction_details' => 'required',
            'inbound_option' => 'required',
            

            
            'inbound_option' => 'required',
            

            'business_operating_hours' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'id'=>'required',
           
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()], 200);
        }

        $up = [];
        $up['region']=$request->region;
        $up['dzongkhag']=$request->dzongkhag;
        $up['dungkhag']=$request->dungkhag;
        $up['gewog']=$request->gewog;
        $up['village']=$request->village;
        $up['destination_name']=$request->destination_name;
        $up['attraction_category']=$request->attraction_category;
        $up['theme_category']=$request->theme_category;
        $up['attraction_details']=$request->attraction_details;
        $up['activity_details']=$request->activity_details;
        $up['inbound_option']=$request->inbound_option;
        $up['local_travel']=$request->local_travel;
        $up['culture_advice']=$request->culture_advice;
        $up['public_holidays']=$request->public_holidays;
        $up['festivals_events']=$request->festivals_events;
        $up['business_operating_hours']=$request->business_operating_hours;
        $up['weather_seasonal_factors']=$request->weather_seasonal_factors;
        $up['amenities']=$request->amenities;
        $up['disable_friendly_services']=$request->disable_friendly_services;
        $up['theme_details']=$request->theme_details;
        $up['accomodation_details']=$request->accomodation_details;
        $up['additional_resources']=$request->additional_resources;
        $up['latitude']=$request->latitude;
        $up['longitude']=$request->longitude;
        $up['contact_person']=$request->contact_person;
        $up['phone_no']=$request->phone_no;
        $up['email']=$request->email;
        $up['mobile']=$request->mobile;
        $up['monument']=$request->monument;
        $up['maximum_capacity']=$request->maximum_capacity;
        $up['cost_of_visit']=$request->cost_of_visit;
        $up['guide_mandatory']=$request->guide_mandatory;
        $up['top_destination']=$request->top_destination;

        if ($request->hasFile('destination_header_image'))
            {
             $destination_header_image = $request->destination_header_image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $destination_header_image->getClientOriginalExtension();
             $destination_header_image->move("storage/app/public/destination_header_image",$filename);
             $up['destination_header_image'] = $filename;
           }

           if ($request->hasFile('destination_teaser_image'))
            {
             $destination_teaser_image = $request->destination_teaser_image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $destination_teaser_image->getClientOriginalExtension();
             $destination_teaser_image->move("storage/app/public/destination_teaser_image",$filename);
             $up['destination_teaser_image'] = $filename;
           }


           if ($request->hasFile('destination_map'))
            {
             $destination_map = $request->destination_map;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $destination_map->getClientOriginalExtension();
             $destination_map->move("storage/app/public/destination_map",$filename);
             $up['destination_map'] = $filename;
           }

           $up['qr_code'] = $request->qr_code;


           if (@$request->qr_code=="1") {

            $name = 'Attraction Name : '.$request->destination_name;
            
            $additional = " Dos and Don't : ".$request->attraction_details;
            $contact = 'Contact Person : '.$request->contact_person;
            $mobile = 'Mobile : '.$request->phone_no;

            $data = 'Attraction Details :-     
                          
    '.$name.'

    '.$additional.'

    '.$contact.'

    '.$mobile.'';


                $request_one = Http::get('https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl='.$data.'');
                if ($request_one->ok()) {
                $filename = time() . '-' . rand(1000, 9999) . '.png';    
                Storage::put($filename, $request_one->body());
                $up['qr_image'] = $filename;
                }

           }

           Poi::where('id',$request->id)->update($up);
           $response['success']= true;
           $response['message'] = 'Poi Updated Successfully';
           return Response::json($response);

        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }  
    }


    public function imageAdd(Request $request)
    {
        $response = [];
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'image' => 'required',
            'id' => 'required',
         ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['poi_id'] = $request->id;
        if ($request->hasFile('image'))
        {
             $image = $request->image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/poi_image",$filename);
             $ins['image'] = $filename;
        }

        PoiImages::create($ins);
        $response['success'] = true;
        $response['message'] = 'Image inserted Successfully';

        return Response::json($response);


        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
    }


    public function imageListing($id)
    {
        $response = [];
        try{
         $images = PoiImages::where('poi_id',$id)->get();
         $response['success'] = true;
         $response['images'] = $images;  
         $response['image_link'] = 'http://services.tourism.gov.bt/storage/app/public/poi_image/'; 
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function imageDelete($id)
    {
        $response = [];
          try{
            PoiImages::where('id',$id)->delete();
            $response['success'] = true;
            $response['message'] = 'Image Deleted Successfully';
            return Response::json($response);
          }catch(\Exception $e){
                $response['error'] = $e->getMessage();
                return Response::json($response);
            }
    }


    public function videoAdd(Request $request)
    {
        $response = [];
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'video' => 'required',
            'id' => 'required',
         ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['poi_id'] = $request->id;
        if ($request->hasFile('video'))
        {
             $image = $request->video;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/poi_video",$filename);
             $ins['video'] = $filename;
        }

        PoiVideo::create($ins);
        $response['success'] = true;
        $response['message'] = 'Video inserted Successfully';

        return Response::json($response);


        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function videoListing($id)
    {
        $response = [];
        try{
         $videos = PoiVideo::where('poi_id',$id)->get();
         $response['success'] = true;
         $response['videos'] = $videos;  
         $response['image_link'] = 'http://services.tourism.gov.bt/storage/app/public/poi_video/'; 
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function videoDelete($id)
    {
        $response = [];
          try{
            PoiVideo::where('id',$id)->delete();
            $response['success'] = true;
            $response['message'] = 'Video Deleted Successfully';
            return Response::json($response);
          }catch(\Exception $e){
                $response['error'] = $e->getMessage();
                return Response::json($response);
            }
    }


    public function topDestination()
    {
        $response = [];
        try{
         $data = Poi::with('dzongkhag_name','dungkhag_name','gewog_name','region_name','village_name','theme_name','attraction_category_name')->where('top_destination','1')->get();
         $response['success'] = true;
         $response['data'] = $data;
         $response['destination_header_image'] = 'http://services.tourism.gov.bt/storage/app/public/destination_header_image/';
         $response['destination_teaser_image'] = 'http://services.tourism.gov.bt/storage/app/public/destination_teaser_image/'; 
         $response['destination_map'] = 'http://services.tourism.gov.bt/storage/app/public/destination_map/'; 
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function nationalPark()
    {
        $response = [];
        try{
         $data = Poi::where('attraction_category',4)->with('dzongkhag_name','dungkhag_name','gewog_name','region_name','village_name','theme_name','attraction_category_name')->where('top_destination','1')->get();
         $response['success'] = true;
         $response['data'] = $data;
         $response['destination_header_image'] = 'http://services.tourism.gov.bt/storage/app/public/destination_header_image/';
         $response['destination_teaser_image'] = 'http://services.tourism.gov.bt/storage/app/public/destination_teaser_image/'; 
         $response['destination_map'] = 'http://services.tourism.gov.bt/storage/app/public/destination_map/'; 
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function dateAdd(Request $request)
    {
        $response = [];
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => 'required',
            'occasion' => 'required',
            'id'=>'required',
         ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $close = new PoiCloseDate;
        $close->start_date = $request->start_date;
        $close->poi_id = $request->id;
        $close->end_date = $request->end_date;
        $close->occasion = $request->occasion;
        $close->save();
        $response['success'] = true;
        $response['message'] = 'Data Added Successfully';
        return $response;
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }

    public function dateListing($id)
    {
        $response = [];
        try{
            $response['success'] = true;
            $response['data'] = PoiCloseDate::where('poi_id',$id)->first();
            return Response::json($response);
          }catch(\Exception $e){
                $response['error'] = $e->getMessage();
                return Response::json($response);
        }
    }


    public function dateEdit($id)
    {
        $response = [];
        try{
            $response['success'] = true;
            $response['data'] = PoiCloseDate::where('id',$id)->first();
            return Response::json($response);
          }catch(\Exception $e){
                $response['error'] = $e->getMessage();
                return Response::json($response);
        }
    }


    public function dateUpdate(Request $request)
    {
        $response = [];
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => 'required',
            'occasion' => 'required',
            'id'=>'required',
         ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $upd = [];
        $upd['start_date'] = $request->start_date;
        $upd['end_date'] = $request->end_date;
        $upd['occasion'] = $request->occasion;
        PoiCloseDate::where('id',$request->id)->update($upd);
        $response['success'] = true;
        $response['message'] = 'Data Updated Successfully';
        return $response;
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function monumentPoi()
    {
        $response = [];
        try{
         $data = Poi::with('dzongkhag_name','dungkhag_name','gewog_name','region_name','village_name','theme_name','attraction_category_name')->where('monument','1')->get();
         $response['success'] = true;
         $response['data'] = $data;
         $response['destination_header_image'] = 'http://services.tourism.gov.bt/storage/app/public/destination_header_image/';
         $response['destination_teaser_image'] = 'http://services.tourism.gov.bt/storage/app/public/destination_teaser_image/'; 
         $response['destination_map'] = 'http://services.tourism.gov.bt/storage/app/public/destination_map/'; 
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }





}  








