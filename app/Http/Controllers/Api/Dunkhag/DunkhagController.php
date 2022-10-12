<?php

namespace App\Http\Controllers\Api\Dunkhag;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dzongkhag;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Response;
use App\Models\Region;
use App\Models\Dungkhag;
use App\Models\Gewog;
class DunkhagController extends Controller
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

    public function add(Request $request)
    {
       $response = [];
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'dzongkhag_id' => 'required',
            'map' => 'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['name'] = $request->name;
        $ins['dzongkhag_id'] = $request->dzongkhag_id;
        

        if ($request->hasFile('map'))
        {
             $image = $request->map;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/map",$filename);
             $ins['map'] = $filename;
        }

        Dungkhag::create($ins);
        $response['success'] = true;
        $response['message'] = 'Dungkhag Added Successfully';
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
         $data = Dungkhag::with('dzongkhag_name')->get();
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
         $data = Dungkhag::where('id',$id)->first();
         $response['success'] = true;
         $response['data'] = $data;
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
            'name' => 'required',
            'dzongkhag_id' => 'required',
            'id' => 'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['name'] = $request->name;
        $ins['dzongkhag_id'] = $request->dzongkhag_id;
        

        if ($request->hasFile('map'))
        {
             $image = $request->map;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/map",$filename);
             $ins['map'] = $filename;
        }

        Dungkhag::where('id',$request->id)->update($ins);
        $response['success'] = true;
        $response['message'] = 'Dungkhag Added Successfully';
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
         $check = Gewog::where('dungkhag_id',$id)->first();
         if (@$check!="") {
             $response['success'] = false;
             $response['message'] = 'Gewog can not be deleted as it has dzongkhag';
             return Response::json($response);
         }   
         $data = Dungkhag::where('id',$id)->delete();
         $response['success'] = true;
         $response['message'] = 'Dungkhag deleted Successfully';   
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }
}
