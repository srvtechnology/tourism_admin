<?php

namespace App\Http\Controllers\Api\Activity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Response;
use App\Models\ActivityCategory;
use App\Models\ActivitySubCategory;
use App\Models\Dzongkhag;
use App\Models\Dungkhag;
use App\Models\Gewog;
use App\Models\Village;
use App\Models\Region;
use App\Models\Activity;
use App\Models\ActivityImage;
use App\Models\ActivityVideo;
use App\Models\ActivityContact;
use App\Models\Review;
class ActivityController extends Controller
{
    public function getSubCategory(Request $request)
    {
        $response = [];
        try{
        
         $subcategory = ActivitySubCategory::where('status','!=','D')->where('category_id',$request->id)->get();
         $response['success'] = true;
         $response['subcategory'] = $subcategory;   
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }

    public function onChnageRegion(Request $request)
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

    public function onChnageDzongkhag(Request $request)
    {
        $response = [];
        
        try{
         $dungkhag = Dungkhag::where('dzongkhag_id',$request->dzongkhag_id)->get();
         $gewog = Gewog::where('dzongkhag_id',$request->dzongkhag_id)->get();
         $response['gewog'] = $gewog;
         $response['village'] = Village::where('dzongkhag_id',$request->dzongkhag_id)->get();   
         $response['dungkhag'] = $dungkhag;   
         $response['success'] = true;
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }

    public function onChnageDungkhag(Request $request)
    {
        $response = [];
        
        try{
         $gewog = Gewog::where('dungkhag_id',$request->dungkhag_id)->get();
         $response['success'] = true;
         $response['gewog'] = $gewog;   
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }

    public function onChnageGewog(Request $request)
    {
        $response = [];
        
        try{
         $village = Village::where('gewog_id',$request->gewog_id)->get();
         $response['success'] = true;
         $response['village'] = $village;   
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }

    }


    public function addView(Request $request)
    {
        $response = [];
        
        try{
         $response['success'] = true;
         $response['regions'] = Region::get();
         $response['category'] = ActivityCategory::where('status','!=','D')->get();   
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
        //valid credential
        $validator = Validator::make($request->all(), [
            'region_id' => 'required',
            'dzongkhag_id' => 'required',
            // 'dungkhag_id'=>'required',
            // 'gewog_id'=>'required',
            // 'village'=>'required',
            'name'=>'required',
            'category_id'=>'required',
            'subcategory_id'=>'required',

            'website'=>'required',
            'email'=>'required',
            'phone'=>'required',


            'mobile'=>'required',
            'whatsapp'=>'required',
            'brief'=>'required',


            'details'=>'required',
            'max_elevation'=>'required',
            'min_elevation'=>'required',

            'difficulty'=>'required',
            'season_duration'=>'required',
            'charges'=>'required',

            'discount'=>'required',
            'weather'=>'required',
            'amenities'=>'required',
            'equipments'=>'required',

            // 'header_image'=>'required',
            // 'teaser_image'=>'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['region_id'] = $request->region_id;
        $ins['dzongkhag_id'] = $request->dzongkhag_id;
        $ins['dungkhag_id'] = $request->dungkhag_id;
        $ins['gewog_id'] = $request->gewog_id;
        $ins['village'] = $request->village;
        $ins['name'] = $request->name;

        $ins['category_id'] = $request->category_id;
        $ins['subcategory_id'] = $request->subcategory_id;
        $ins['website'] = $request->website;
        $ins['email'] = $request->email;

        $ins['phone'] = $request->phone;
        $ins['mobile'] = $request->mobile;
        $ins['whatsapp'] = $request->whatsapp;
        $ins['brief'] = $request->brief;

        $ins['details'] = $request->details;
        $ins['max_elevation'] = $request->max_elevation;
        $ins['min_elevation'] = $request->min_elevation;
        $ins['difficulty'] = $request->difficulty;

        $ins['season_duration'] = $request->season_duration;
        $ins['charges'] = $request->charges;
        $ins['discount'] = $request->discount;
        $ins['weather'] = $request->weather;

        $ins['amenities'] = $request->amenities;
        $ins['equipments'] = $request->equipments;


        if ($request->hasFile('header_image'))
        {
             $header_image = $request->header_image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $header_image->getClientOriginalExtension();
             $header_image->move("storage/app/public/activity_image",$filename);
             $ins['header_image'] = $filename;
        }

        if ($request->hasFile('teaser_image'))
        {
             $teaser_image = $request->teaser_image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $teaser_image->getClientOriginalExtension();
             $teaser_image->move("storage/app/public/activity_image",$filename);
             $ins['teaser_image'] = $filename;
        }
        
        Activity::create($ins);
        $response['success'] = true;
        $response['message'] = 'Activity Added Successfully';
        return $response;




        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function listing()
    {
        $response = [];
        
        try{
         $data = Activity::where('status','!=','D')->with('region_name','dungkhag_name','dzongkhag_name','gewog_name','Village_name','category_name','subcategory_name');
         if (@$request->category_id) {
             $data = $data->where('category_id',$request->category_id);
             $response['subcategory'] = ActivitySubCategory::where('category_id',$request->category_id)->get();
         }
         if (@$request->subcategory_id) {
             $data = $data->where(' subcategory_id',$request->subcategory_id);
         }

         if (@$request->region_id) {
             $data = $data->where('region_id',$request->region_id);
             $response['dzongkhag'] = Dzongkhag::where('region_id',@$request->region_id)->get();
         }

         if (@$request->dzongkhag_id) {
              $data = $data->where('dzongkhag_id',$request->dzongkhag_id);
              $response['dungkhag'] = Dungkhag::where('dzongkhag_id',$request->dzongkhag_id)->get();
         }

         if (@$request->dungkhag_id) {
              $data = $data->where('dungkhag_id',$request->dungkhag_id);
              $response['gewog'] = Gewog::where('dungkhag_id',@$request->dungkhag_id)->get();
         }

         if (@$request->gewog_id) {
            $data = $data->where('gewog_id',$request->gewog_id);
         }

         $data = $data->get();  



         $response['success'] = true;
         $response['datas'] = $data;   
         $response['regions'] = Region::get();
         $response['category'] = ActivityCategory::where('status','!=','D')->get();
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }

    public function delete($id)
    {
        $response = [];
        Activity::where('id',$id)->update(['status'=>'D']);
        $response['success'] = true;
        $response['message'] = 'Activity Deleted Successfully';
        return $response;

    }


    public function edit($id)
    {
        $response = [];
        
        try{
         $response['data'] = Activity::where('id',$id)->first();
         $response['category'] = ActivityCategory::where('status','!=','D')->get();
         $response['subcategory'] = ActivitySubCategory::where('category_id',$response['data']->category_id)->where('status','!=','D')->get();
         $response['regions'] = Region::get();
         $response['dzongkhag'] = Dzongkhag::where('region_id',$response['data']->region_id)->get();
         $response['dungkhag'] = Dungkhag::where('dzongkhag_id',$response['data']->dzongkhag_id)->get();
         $response['gewog'] = Gewog::where('dungkhag_id',$response['data']->dungkhag_id)->get();
         $response['village'] = Village::where('gewog_id',$response['data']->gewog_id)->get();
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
        //valid credential
        $validator = Validator::make($request->all(), [
            'region_id' => 'required',
            'dzongkhag_id' => 'required',
            // 'dungkhag_id'=>'required',
            // 'gewog_id'=>'required',
            // 'village'=>'required',
            'name'=>'required',
            'category_id'=>'required',
            'subcategory_id'=>'required',

            'website'=>'required',
            'email'=>'required',
            'phone'=>'required',


            'mobile'=>'required',
            'whatsapp'=>'required',
            'brief'=>'required',


            'details'=>'required',
            'max_elevation'=>'required',
            'min_elevation'=>'required',

            'difficulty'=>'required',
            'season_duration'=>'required',
            'charges'=>'required',

            'discount'=>'required',
            'weather'=>'required',
            'amenities'=>'required',
            'equipments'=>'required',
            'id'=>'required',

        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['region_id'] = $request->region_id;
        $ins['dzongkhag_id'] = $request->dzongkhag_id;
        $ins['dungkhag_id'] = $request->dungkhag_id;
        $ins['gewog_id'] = $request->gewog_id;
        $ins['village'] = $request->village;
        $ins['name'] = $request->name;

        $ins['category_id'] = $request->category_id;
        $ins['subcategory_id'] = $request->subcategory_id;
        $ins['website'] = $request->website;
        $ins['email'] = $request->email;

        $ins['phone'] = $request->phone;
        $ins['mobile'] = $request->mobile;
        $ins['whatsapp'] = $request->whatsapp;
        $ins['brief'] = $request->brief;

        $ins['details'] = $request->details;
        $ins['max_elevation'] = $request->max_elevation;
        $ins['min_elevation'] = $request->min_elevation;
        $ins['difficulty'] = $request->difficulty;

        $ins['season_duration'] = $request->season_duration;
        $ins['charges'] = $request->charges;
        $ins['discount'] = $request->discount;
        $ins['weather'] = $request->weather;

        $ins['amenities'] = $request->amenities;
        $ins['equipments'] = $request->equipments;


        if ($request->hasFile('header_image'))
        {
             $header_image = $request->header_image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $header_image->getClientOriginalExtension();
             $header_image->move("storage/app/public/header_image",$filename);
             $ins['header_image'] = $filename;
        }

        if ($request->hasFile('teaser_image'))
        {
             $teaser_image = $request->teaser_image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $teaser_image->getClientOriginalExtension();
             $teaser_image->move("storage/app/public/teaser_image",$filename);
             $ins['teaser_image'] = $filename;
        }
        
        Activity::where('id',$request->id)->update($ins);
        $response['success'] = true;
        $response['message'] = 'Activity Updated Successfully';
        return $response;




        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function imageUpload(Request $request)
    {
        $response = [];
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'image' => 'required',
            'id'=>'required',
         ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['activity_id'] = $request->id;
        if ($request->hasFile('image'))
        {
             $image = $request->image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/activity_image",$filename);
             $ins['image'] = $filename;
        }

        ActivityImage::create($ins);
        $response['success'] = true;
        $response['message'] = 'Images inserted Successfully';
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
         $images = ActivityImage::where('activity_id',$id)->get();
         $response['success'] = true;
         $response['images'] = $images;   
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
        $img = ActivityImage::where('id',$id)->first();  
        @unlink('storage/app/public/activity_image/'.$img->image);  
        ActivityImage::where('id',$id)->delete();
        $response['success'] = true;
        $response['message'] = 'Image Deleted Successfully';
        return Response::json($response);

        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }  
    }


    public function videoUpload(Request $request)
    {
        $response = [];
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'video' => 'required',
            'id'=>'required',
         ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['activity_id'] = $request->id;
        
        if ($request->hasFile('video'))
        {
             $video = $request->video;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $video->getClientOriginalExtension();
             $video->move("storage/app/public/activity_video",$filename);
             $ins['video'] = $filename;
        }

        ActivityVideo::create($ins);
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
         $videos = ActivityVideo::where('activity_id',$id)->get();
         $response['success'] = true;
         $response['videos'] = $videos;   
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
        $img = ActivityVideo::where('id',$id)->first();  
        @unlink('storage/app/public/activity_video/'.$img->video);  
        ActivityVideo::where('id',$id)->delete();
        $response['success'] = true;
        $response['message'] = 'Video Deleted Successfully';
        return Response::json($response);

        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
    }


    public function frontActivity()
    {
        $response = [];
        
        try{
         $response['success'] = true;               
         $response['data'] = Activity::with('images')->where('status','!=','D')->with('region_name','dungkhag_name','dzongkhag_name','gewog_name','Village_name','category_name','subcategory_name')->get();
         $response['image_link'] = 'http://services.tourism.gov.bt/storage/app/public/activity_image/';
         return $response;

         }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
    }


    public function frontActivityDetails($id)
    {
        $response = [];
        
        try{
         $response['success'] = true;               
         $response['data'] = Activity::with('images','videos')->where('status','!=','D')->with('region_name','dungkhag_name','dzongkhag_name','gewog_name','Village_name','category_name','subcategory_name')->where('id',$id)->first();


         // review
         $response['reviews'] = Review::where('activity_id',$id)->get();
         $response['total_review_count'] = Review::where('activity_id',$id)->count();
         $response['avg_review'] = Review::where('activity_id',$id)->avg('rating');
         $response['one_star'] = Review::where('activity_id',$id)->where('rating',1)->count();
         $response['two_star'] = Review::where('activity_id',$id)->where('rating',2)->count();
         $response['three_star'] = Review::where('activity_id',$id)->where('rating',3)->count();
         $response['four_star'] = Review::where('activity_id',$id)->where('rating',4)->count();
         $response['five_star'] = Review::where('activity_id',$id)->where('rating',5)->count();

         
         $response['image_link'] = 'http://services.tourism.gov.bt/storage/app/public/activity_image/';
         $response['video_link'] = 'http://services.tourism.gov.bt/storage/app/public/activity_video/';
         return $response;

         }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function contactListing($id)
    {
        $response = [];
        try{
         $data = ActivityContact::where('activity_id',$id)->get();
         $response['success'] = true;
         $response['data'] = $data;   
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
    }


    public function contactAdd(Request $request)
    {
        $response = [];
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'activity_id'=>'required',
         ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['activity_id'] = $request->activity_id;
        $ins['name'] = $request->name;
        $ins['phone'] = $request->phone;
        $ins['mobile'] = $request->mobile;
        $ins['email'] = $request->email;
        $ins['whatsapp'] = $request->whatsapp;
        
        ActivityContact::create($ins);
        $response['success'] = true;
        $response['message'] = 'Contact inserted Successfully';
        return Response::json($response);


        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function contactEdit($id)
    {
        $response = [];
        try{
         $data = ActivityContact::where('id',$id)->first();
         $response['success'] = true;
         $response['data'] = $data;   
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
    }


    public function contactUpdate(Request $request)
    {
        $response = [];
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'id'=>'required',
         ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        
        $ins['name'] = $request->name;
        $ins['phone'] = $request->phone;
        $ins['mobile'] = $request->mobile;
        $ins['email'] = $request->email;
        $ins['whatsapp'] = $request->whatsapp;
        
        ActivityContact::where('id',$request->id)->update($ins);
        $response['success'] = true;
        $response['message'] = 'Contact inserted Successfully';
        return Response::json($response);


        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function contactDelete($id)
    {
        $response = [];
        try{
            ActivityContact::where('id',$id)->delete();
            $response['success'] = true;
            $response['message'] = 'Contact deleted Successfully';
            return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }    
    }


    public function categoryFetch()
    {
        $response = [];
        try{
         $data = ActivityCategory::where('status','!=','D')->with('subcategory')->get();
         $response['success'] = true;
         $response['data'] = $data;   
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }



    public function subCategoryActivityFetch($id)
    {
        $response = [];
        
        try{
         $response['success'] = true;               
         $response['data'] = Activity::where('subcategory_id',$id)->with('images','videos')->where('status','!=','D')->with('region_name','dungkhag_name','dzongkhag_name','gewog_name','Village_name','category_name','subcategory_name')->get();
         $response['image_link'] = 'http://services.tourism.gov.bt/storage/app/public/activity_image/';
         $response['video_link'] = 'http://services.tourism.gov.bt/storage/app/public/activity_video/';
         return $response;

         }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
    }


    public function categoryActivityFetch($id)
    {
        $response = [];
        
        try{
         $response['success'] = true;               
         $response['data'] = Activity::where('category_id',$id)->with('images','videos')->where('status','!=','D')->with('region_name','dungkhag_name','dzongkhag_name','gewog_name','Village_name','category_name','subcategory_name')->get();
         $response['image_link'] = 'http://services.tourism.gov.bt/storage/app/public/activity_image/';
         $response['video_link'] = 'http://services.tourism.gov.bt/storage/app/public/activity_video/';
         return $response;

         }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function regionActivityFetch($id)
    {
        $response = [];
        
        try{
         $response['success'] = true;               
         $response['data'] = Activity::where('region_id',$id)->with('images','videos')->where('status','!=','D')->with('region_name','dungkhag_name','dzongkhag_name','gewog_name','Village_name','category_name','subcategory_name')->get();
         $response['image_link'] = 'http://services.tourism.gov.bt/storage/app/public/activity_image/';
         $response['video_link'] = 'http://services.tourism.gov.bt/storage/app/public/activity_video/';
         return $response;

         }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function dzonkhagActivityFetch($id)
    {
        $response = [];
        
        try{
         $response['success'] = true;               
         $response['data'] = Activity::where('dzongkhag_id',$id)->with('images','videos')->where('status','!=','D')->with('region_name','dungkhag_name','dzongkhag_name','gewog_name','Village_name','category_name','subcategory_name')->get();
         $response['image_link'] = 'http://services.tourism.gov.bt/storage/app/public/activity_image/';
         $response['video_link'] = 'http://services.tourism.gov.bt/storage/app/public/activity_video/';
         return $response;

         }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    

}
