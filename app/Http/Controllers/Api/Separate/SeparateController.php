<?php

namespace App\Http\Controllers\Api\Separate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Response;
use App\Models\HotelCategory;
use App\Models\HotelSeparate;
use App\Models\TourOparator;
use App\Models\Guide;

class SeparateController extends Controller
{
    public function categoryAdd(Request $request)
    {
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'description' => 'required',
            'id'=>'required',
            'token'=>'required',
        ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        if (@$request->token=="tcbseparatedatasaveupdate") {
            $ins = [];
        
            $ins['id'] = $request->id;
            $ins['name'] = $request->name;
            $ins['description'] = $request->phone;
            
            
            HotelCategory::create($ins);
            $response['success'] = true;
            $response['message'] = 'Hotel Category Added Successfully';
        }else{
            $response['success'] = false;
            $response['message'] = 'Invalid token';
        }

        
        return Response::json($response);


        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function categoryUpdate(Request $request)
    {
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'id'=>'required',
            'token'=>'required',
        ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        if (@$request->token=="tcbseparatedatasaveupdate") {

        $ins = [];
        
        $ins['id'] = $request->id;
        $ins['name'] = $request->name;
        $ins['description'] = $request->phone;
        
        
        HotelCategory::where('id',$request->id)->update($ins);
        $response['success'] = true;
        $response['message'] = 'Hotel Category Updated Successfully';

        }else{
            $response['success'] = false;
            $response['message'] = 'Invalid token';
        }
        return Response::json($response);


        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function hotelAdd(Request $request)
    {
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'dzongkhag_id' => 'required',
            'hotel_name' => 'required',
            'license_no'=>'required',
            
            
            'status'=>'required',
            'token'=>'required',
            'id'=>'required',
        ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        if (@$request->token=="tcbseparatedatasaveupdate") {
            $ins = [];
        
            $ins['id'] = $request->id;
            $ins['dzongkhag_id'] = $request->dzongkhag_id;
            $ins['hotel_name'] = $request->hotel_name;
            $ins['license_no'] = $request->license_no;
            $ins['email'] = $request->email;

            $ins['phone'] = $request->phone;
            $ins['mobile_no'] = $request->mobile_no;
            $ins['status'] = $request->status;
            
            
            HotelSeparate::create($ins);
            $response['success'] = true;
            $response['message'] = 'Hotel Added Successfully';
        }else{
            $response['success'] = false;
            $response['message'] = 'Invalid token';
        }

        
        return Response::json($response);


        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }   
    }


    public function hotelUpdate(Request $request)
    {
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'dzongkhag_id' => 'required',
            'hotel_name' => 'required',
            'license_no'=>'required',
            
            
            'status'=>'required',
            'token'=>'required',
            'id'=>'required',
        ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        if (@$request->token=="tcbseparatedatasaveupdate") {

            $ins['id'] = $request->id;
            $ins['dzongkhag_id'] = $request->dzongkhag_id;
            $ins['hotel_name'] = $request->hotel_name;
            $ins['license_no'] = $request->license_no;
            $ins['email'] = $request->email;

            $ins['phone'] = $request->phone;
            $ins['mobile_no'] = $request->mobile_no;
            $ins['status'] = $request->status;
            
            
            HotelSeparate::where('id',$request->id)->update($ins);
            $response['success'] = true;
            $response['message'] = 'Hotel Updated Successfully';
        
        
        
        $response['success'] = true;
        $response['message'] = 'Hotel Category Updated Successfully';

        }else{
            $response['success'] = false;
            $response['message'] = 'Invalid token';
        }
        return Response::json($response);


        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }



    public function tourOparatorAdd(Request $request)
    {
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'dzongkhag_id' => 'required',
            'name' => 'required',
            'license_no'=>'required',
            'status'=>'required',
            'token'=>'required',
            'id'=>'required',
        ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        if (@$request->token=="tcbseparatedatasaveupdate") {
            $ins = [];
        
            $ins['id'] = $request->id;
            $ins['dzongkhag_id'] = $request->dzongkhag_id;
            $ins['name'] = $request->name;
            $ins['license_no'] = $request->license_no;
            $ins['email'] = $request->email;

            $ins['phone'] = $request->phone;
            $ins['mobile_no'] = $request->mobile_no;
            $ins['status'] = $request->status;
            
            
            TourOparator::create($ins);
            $response['success'] = true;
            $response['message'] = 'Tour Oparator Added Successfully';
        }else{
            $response['success'] = false;
            $response['message'] = 'Invalid token';
        }

        
        return Response::json($response);


        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function tourOparatorUpdate(Request $request)
    {
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'dzongkhag_id' => 'required',
            'name' => 'required',
            'license_no'=>'required',
            'status'=>'required',
            'token'=>'required',
            'id'=>'required',
        ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        if (@$request->token=="tcbseparatedatasaveupdate") {
            $ins = [];
        
            $ins['id'] = $request->id;
            $ins['dzongkhag_id'] = $request->dzongkhag_id;
            $ins['name'] = $request->name;
            $ins['license_no'] = $request->license_no;
            $ins['email'] = $request->email;

            $ins['phone'] = $request->phone;
            $ins['mobile_no'] = $request->mobile_no;
            $ins['status'] = $request->status;
            
            
            TourOparator::where('id',$request->id)->update($ins);
            $response['success'] = true;
            $response['message'] = 'Tour Oparator Updated Successfully';
        }else{
            $response['success'] = false;
            $response['message'] = 'Invalid token';
        }

        
        return Response::json($response);


        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function guideAdd(Request $request)
    {
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'dzongkhag_id' => 'required',
            'name' => 'required',
            'guide_cid'=>'required',
            'license_no'=>'required',
            'token'=>'required',
            'id'=>'required',
            'status'=>'required',
        ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        if (@$request->token=="tcbseparatedatasaveupdate") {
            $ins = [];
        
            $ins['id'] = $request->id;
            $ins['dzongkhag_id'] = $request->dzongkhag_id;
            $ins['name'] = $request->name;
            $ins['license_no'] = $request->license_no;
            $ins['email'] = $request->email;
            $ins['guide_cid'] = $request->guide_cid;

            $ins['phone'] = $request->phone;
            $ins['mobile_no'] = $request->mobile_no;
            $ins['status'] = $request->status;
            
            
            Guide::create($ins);
            $response['success'] = true;
            $response['message'] = 'Guide Added Successfully';
        }else{
            $response['success'] = false;
            $response['message'] = 'Invalid token';
        }

        
        return Response::json($response);


        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }



    public function guideUpdate(Request $request)
    {
                try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'dzongkhag_id' => 'required',
            'name' => 'required',
            'guide_cid'=>'required',
            'license_no'=>'required',
            'token'=>'required',
            'id'=>'required',
            'status'=>'required',
        ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        if (@$request->token=="tcbseparatedatasaveupdate") {
            $ins = [];
        
            $ins['id'] = $request->id;
            $ins['dzongkhag_id'] = $request->dzongkhag_id;
            $ins['name'] = $request->name;
            $ins['license_no'] = $request->license_no;
            $ins['email'] = $request->email;
            $ins['guide_cid'] = $request->guide_cid;

            $ins['phone'] = $request->phone;
            $ins['mobile_no'] = $request->mobile_no;
            $ins['status'] = $request->status;
            
            
            Guide::where('id',$request->id)->update($ins);
            $response['success'] = true;
            $response['message'] = 'Guide Updated Successfully';
        }else{
            $response['success'] = false;
            $response['message'] = 'Invalid token';
        }

        
        return Response::json($response);


        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    } 
}
