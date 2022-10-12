<?php

namespace App\Http\Controllers\Api\Region;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\Dzongkhag;
use App\Models\Poi;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Response;
class RegionController extends Controller
{
    public function add(Request $request)
    {
       $response = [];
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'region_description' => 'required',
            'region_header_image' => 'required',
            'region_map' => 'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['name'] = $request->name;
        $ins['region_description'] = $request->region_description;
        
        if ($request->hasFile('region_header_image'))
        {
             $image = $request->region_header_image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/region_header_image",$filename);
             $ins['region_header_image'] = $filename;
        }

        if ($request->hasFile('region_map'))
        {
             $image = $request->region_map;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/region_map",$filename);
             $ins['region_map'] = $filename;
        }

        Region::create($ins);
        $response['success'] = true;
        $response['message'] = 'Region Added Successfully';
        return Response::json($response);

        
        

        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function listing()
    {
        $response = [];
        try{
         $data = Region::get();
         $response['success'] = true;
         $response['data'] = $data;
         $response['region_header_image'] = 'http://services.tourism.gov.bt/storage/app/public/region_header_image/'; 
         $response['region_map_link'] = 'http://services.tourism.gov.bt/storage/app/public/region_map/';   
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
         $data = Region::where('id',$id)->first();
         $response['success'] = true;
         $response['data'] = $data;
         $response['region_header_image'] = 'http://services.tourism.gov.bt/storage/app/public/region_header_image/'; 
         $response['region_map_link'] = 'http://services.tourism.gov.bt/storage/app/public/region_map/';   
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
            'name' => 'required',
            'region_description' => 'required',
            'id'=>'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $img = Region::where('id',$request->id)->first();

        $ins = [];
        $ins['name'] = $request->name;
        $ins['region_description'] = $request->region_description;
        
        if ($request->hasFile('region_header_image'))
        {
             @unlink('storage/app/public/region_header_image/'.$img->region_header_image);
             $image = $request->region_header_image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/region_header_image",$filename);
             $ins['region_header_image'] = $filename;
        }

        if ($request->hasFile('region_map'))
        {
             @unlink('storage/app/public/region_map/'.$img->region_map);
             $image = $request->region_map;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/region_map",$filename);
             $ins['region_map'] = $filename;
        }

        Region::where('id',$request->id)->update($ins);
        $response['success'] = true;
        $response['message'] = 'Region Updated Successfully';
        return Response::json($response);

        
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
         $check = Dzongkhag::where('region_id',$id)->first();
         if (@$check!="") {
             $response['success'] = false;
             $response['message'] = 'Region can not be deleted as it has dzongkhag';
             return Response::json($response);
         }   
         $data = Region::where('id',$id)->delete();
         $response['success'] = true;
         $response['message'] = 'Region deleted Successfully';   
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function allRegion()
    {
        $response = [];
        try{
         $data = Region::with('dzongkhags')->get();
         $response['success'] = true;
         $response['data'] = $data;
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function regionDetails($id)
    {
        $response = [];
        try{
         $data = Region::where('id',$id)->first();
         $response['success'] = true;
         $response['data'] = $data;
         $response['region_header_image'] = 'http://services.tourism.gov.bt/storage/app/public/region_header_image/'; 
         $response['region_map_link'] = 'http://services.tourism.gov.bt/storage/app/public/region_map/';
         $response['dzongkhag'] = Dzongkhag::where('region_id',$id)->get();
         $response['dzongkhag_header_image'] = 'http://services.tourism.gov.bt/storage/app/public/dzongkhag_header_image/'; 
         $response['dzongkhag_teaser_image'] = 'http://services.tourism.gov.bt/storage/app/public/dzongkhag_teaser_image/';  


         // poi/////////////////////////////////////////////////////////////////////////////////////

         $response['poi'] = Poi::where('region',$id)->with('dzongkhag_name','dungkhag_name','gewog_name','region_name','village_name','theme_name','attraction_category_name')->get();
          
         $response['destination_header_image'] = 'http://services.tourism.gov.bt/storage/app/public/destination_header_image/';
         $response['destination_teaser_image'] = 'http://services.tourism.gov.bt/storage/app/public/destination_teaser_image/'; 
         $response['destination_map'] = 'http://services.tourism.gov.bt/storage/app/public/destination_map/';


         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function dzongkhagDetails($id)
    {
        $response = [];
        try{
         $response['success'] = true;
         $data = Dzongkhag::where('id',$id)->first();
         $response['dzongkhag_header_image'] = 'http://services.tourism.gov.bt/storage/app/public/dzongkhag_header_image/'; 
         $response['dzongkhag_teaser_image'] = 'http://services.tourism.gov.bt/storage/app/public/dzongkhag_teaser_image/'; 
         $response['data'] = $data;
         
         // poi/////////////////////////////////////////////////////////////////////////////////////

         $response['poi'] = Poi::where('dzongkhag',$id)->with('dzongkhag_name','dungkhag_name','gewog_name','region_name','village_name','theme_name','attraction_category_name')->get();
          
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
