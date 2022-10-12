<?php

namespace App\Http\Controllers\Api\Gewog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Response;
use App\Models\Gewog;
use App\Models\Village;
use App\Models\Dzongkhag;
use App\Models\Dungkhag;

class GewogController extends Controller
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


    public function getDunkhag(Request $request)
    {
        $response = [];
        
        try{
         $dungkhag = Dungkhag::where('dzongkhag_id',$request->dzongkhag_id)->get();
         $gewog = Gewog::where('dzongkhag_id',$request->dzongkhag_id)->get();
         $response['success'] = true;
         $response['dungkhag'] = $dungkhag;  
         $response['gewog'] = $gewog;
         $response['village'] = Village::where('dzongkhag_id',$request->dzongkhag_id)->get();   
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
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['dzongkhag_id'] = $request->dzongkhag_id;
        $ins['name'] = $request->name;
        $ins['dungkhag_id'] = $request->dungkhag_id;

        if ($request->hasFile('map'))
        {
             $image = $request->map;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/map",$filename);
             $ins['map'] = $filename;
        }
        

        Gewog::create($ins);
        $response['success'] = true;
        $response['message'] = 'Gewog Added Successfully';
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
         $data = Gewog::with('dzongkhag_name','dunkhag_name')->get();
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
         $data = Gewog::where('id',$id)->first();
         $response['success'] = true;
         $response['data'] = $data;
         $response['dzongkhag'] = Dzongkhag::get();
         $response['dungkhag'] = Dungkhag::where('dzongkhag_id',$data->dzongkhag_id)->get();
         $response['map'] = 'http://services.tourism.gov.bt/storage/app/public/map/';   
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
         $check = Gewog::where('id',$id)->delete();
         $response['success'] = true;
         $response['message'] = 'Gewog deleted Successfully';   
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
            'id'=>'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['dzongkhag_id'] = $request->dzongkhag_id;
        $ins['name'] = $request->name;
        $ins['dungkhag_id'] = $request->dungkhag_id;

        if ($request->hasFile('map'))
        {
             $image = $request->map;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/map",$filename);
             $ins['map'] = $filename;
        }
        

        Gewog::where('id',$request->id)->update($ins);
        $response['success'] = true;
        $response['message'] = 'Gewog Updated Successfully';
        return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }
}
