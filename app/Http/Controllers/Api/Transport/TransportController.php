<?php

namespace App\Http\Controllers\Api\Transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Response;
use App\Models\TrasnportImage;
use App\Models\TrasnportVideo;
use App\Models\Transport;
use App\Models\ServiceProvider;
class TransportController extends Controller
{

    public function addView()
    {
        $response = [];
        
        try{
         $response['success'] = true;
         $response['regions'] = Region::get();
         $response['category'] = ServiceProvider::where('status','!=','D')->get();   
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
            'dungkhag_id'=>'required',
            'gewog_id'=>'required',
            'village'=>'required',
            'name'=>'required',
            'category_id'=>'required',
            

            'website'=>'required',
            'email'=>'required',
            'phone'=>'required',


            'mobile'=>'required',
            'whatsapp'=>'required',
            


            'details'=>'required',
            'min_charge'=>'required',
            'max_charge'=>'required',

            'price_details'=>'required',
            'available_motorcycle'=>'required',
            'discount_information'=>'required',

            'rechable_destination'=>'required',
            'rechable_event'=>'required',
            'lat'=>'required',
            'lon'=>'required',


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
        
        $ins['website'] = $request->website;
        $ins['email'] = $request->email;

        $ins['phone'] = $request->phone;
        $ins['mobile'] = $request->mobile;
        $ins['whatsapp'] = $request->whatsapp;
        

        $ins['details'] = $request->details;
        $ins['min_charge'] = $request->min_charge;
        $ins['max_charge'] = $request->max_charge;
        $ins['price_details'] = $request->price_details;

        $ins['available_motorcycle'] = $request->available_motorcycle;
        $ins['discount_information'] = $request->discount_information;
        $ins['rechable_destination'] = $request->rechable_destination;
        $ins['rechable_event'] = $request->rechable_event;

        $ins['lat'] = $request->lat;
        $ins['lon'] = $request->lon;
        
        Transport::create($ins);
        $response['success'] = true;
        $response['message'] = 'Transport Added Successfully';
        return $response;




        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }

    public function list()
    {
        $response = [];
        
        try{
         $data = Transport::where('status','!=','D')->with('region_name','dungkhag_name','dzongkhag_name','gewog_name','Village_name')->get();
         $response['success'] = true;
         $response['data'] = $data;   
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
        Transport::where('id',$id)->update(['status'=>'D']);
        $response['success'] = true;
        $response['message'] = 'Transport Deleted Successfully';
        return $response;
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }

    }

    public function edit($id)
    {
        $response = [];
        
        try{
         $response['data'] = Transport::where('id',$id)->first();
         $response['category'] = ServiceProvider::where('status','!=','D')->get();
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
            'dungkhag_id'=>'required',
            'gewog_id'=>'required',
            'village'=>'required',
            'name'=>'required',
            'category_id'=>'required',
            

            'website'=>'required',
            'email'=>'required',
            'phone'=>'required',


            'mobile'=>'required',
            'whatsapp'=>'required',
            


            'details'=>'required',
            'min_charge'=>'required',
            'max_charge'=>'required',

            'price_details'=>'required',
            'available_motorcycle'=>'required',
            'discount_information'=>'required',

            'rechable_destination'=>'required',
            'rechable_event'=>'required',
            'lat'=>'required',
            'lon'=>'required',
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
        
        $ins['website'] = $request->website;
        $ins['email'] = $request->email;

        $ins['phone'] = $request->phone;
        $ins['mobile'] = $request->mobile;
        $ins['whatsapp'] = $request->whatsapp;
        

        $ins['details'] = $request->details;
        $ins['min_charge'] = $request->min_charge;
        $ins['max_charge'] = $request->max_charge;
        $ins['price_details'] = $request->price_details;

        $ins['available_motorcycle'] = $request->available_motorcycle;
        $ins['discount_information'] = $request->discount_information;
        $ins['rechable_destination'] = $request->rechable_destination;
        $ins['rechable_event'] = $request->rechable_event;

        $ins['lat'] = $request->lat;
        $ins['lon'] = $request->lon;
        
        Transport::where('id',$request->id)->update($ins);
        $response['success'] = true;
        $response['message'] = 'Transport Added Successfully';
        return $response;




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
            'id'=>'required',
         ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['trasnport_id'] = $request->id;
        if ($request->hasFile('image'))
        {
             $image = $request->image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/transport_image",$filename);
             $ins['image'] = $filename;
        }

        TrasnportImage::create($ins);
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
         $images = TrasnportImage::where('trasnport_id',$id)->get();
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
        $img = TrasnportImage::where('id',$id)->first();  
        @unlink('storage/app/public/transport_image/'.$img->image);  
        TrasnportImage::where('id',$id)->delete();
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
            'id'=>'required',
         ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['trasnport_id'] = $request->id;
        
        if ($request->hasFile('video'))
        {
             $video = $request->video;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $video->getClientOriginalExtension();
             $video->move("storage/app/public/trasnport_video",$filename);
             $ins['video'] = $filename;
        }

        TrasnportVideo::create($ins);
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
         $videos = TrasnportVideo::where('trasnport_id',$id)->get();
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
        $img = TrasnportVideo::where('id',$id)->first();  
        @unlink('storage/app/public/trasnport_video/'.$img->video);  
        TrasnportVideo::where('id',$id)->delete();
        $response['success'] = true;
        $response['message'] = 'Video Deleted Successfully';
        return Response::json($response);

        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }
}
