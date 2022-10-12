<?php

namespace App\Http\Controllers\Api\Village;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Response;
use App\Models\Gewog;
use App\Models\Dzongkhag;
use App\Models\Dungkhag;
use App\Models\Village;

class VillageController extends Controller
{
    public function addView()
    {
        $response = [];
        try{
         $data = Dzongkhag::get();
         $response['success'] = true;
         $response['dzongkhag'] = $data;
         return Response::json($response);
         }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function getGewog(Request $request)
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

    public function add(Request $request)
    {
        $response = [];
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'dzongkhag_id' => 'required',
            'name' => 'required',
            'gewog_id' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['dzongkhag_id'] = $request->dzongkhag_id;
        $ins['dungkhag_id'] = $request->dungkhag_id;
        $ins['gewog_id'] = $request->gewog_id;
        $ins['name'] = $request->name;


        if ($request->hasFile('map'))
        {
             $image = $request->map;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/map",$filename);
             $ins['map'] = $filename;
        }
        

        Village::create($ins);
        $response['success'] = true;
        $response['message'] = 'Village Added Successfully';
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
         $data = Village::with('dzongkhag_name','dungkhag_name','gewog_name')->get();
         $response['success'] = true;
         $response['data'] = $data;
         $response['map'] = 'http://services.tourism.gov.bt/storage/app/public/map/'; 
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
         $data = Village::where('id',$id)->first();
         $response['success'] = true;
         $response['data'] = $data;
         $response['dzongkhag'] = Dzongkhag::get();
         $response['dungkhag'] = Dungkhag::where('dzongkhag_id',$data->dzongkhag_id)->get();
         $response['gewog'] = Gewog::where('dzongkhag_id',$data->dzongkhag_id)->get();
         $response['map'] = 'http://services.tourism.gov.bt/storage/app/public/map/';   
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
            'dzongkhag_id' => 'required',
            'name' => 'required',
            'gewog_id' => 'required',
            'id'=>'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['dzongkhag_id'] = $request->dzongkhag_id;
        $ins['dungkhag_id'] = $request->dungkhag_id;
        $ins['gewog_id'] = $request->gewog_id;
        $ins['name'] = $request->name;


        if ($request->hasFile('map'))
        {
             $image = $request->map;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/map",$filename);
             $ins['map'] = $filename;
        }
        

        Village::where('id',$request->id)->update($ins);
        $response['success'] = true;
        $response['message'] = 'Village Updated Successfully';
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
         $check = Village::where('id',$id)->delete();
         $response['success'] = true;
         $response['message'] = 'Village deleted Successfully';   
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }

    





}
