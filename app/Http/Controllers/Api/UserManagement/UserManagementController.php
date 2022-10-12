<?php

namespace App\Http\Controllers\Api\UserManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use App\Models\User;
use App\Models\HotelCategory;
use App\Models\HotelSeparate;
use App\Models\Dzongkhag;
use App\Models\Poi;
use Response;

class UserManagementController extends Controller
{
    public function addView()
    {
        $response = [];
        try{
         $response['success'] = true;   
         $response['hotel_category'] = HotelCategory::get();
         $response['dzongkhag'] = Dzongkhag::get();
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
            'last_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'password' => 'required',
            'role' => 'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $check = User::where('email',$request->email)->first();
        if (@$check!="") {
            $response['success'] = false;
            $response['message'] = 'Email Already Added.Please Try Another One';
            return Response::json($response);
        }

        $ins = new User;
        $ins->name = $request->name;
        $ins->middle_name = $request->middle_name;
        $ins->document_no = $request->document_no;
        $ins->last_name = $request->last_name;
        $ins->phone = $request->phone;
        $ins->role = $request->role;
        $ins->dzongkhag_id = $request->dzongkhag_id;
        $ins->hotel_category = $request->hotel_category;
        $ins->hotel_id = $request->hotel_id;
        $ins->poi_id = $request->poi_id;
        $ins->email = $request->email;
        $ins->password = \Hash::make($request->password);

        $ins->save();
        $response['success'] = true;
        $response['message'] = 'User Added Successfully';
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
         $response['success'] = true;   
         $response['users'] = User::where('role','!=','SA')->where('status','!=','D')->get();
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
         $response['success'] = true;   
         $response['data'] = User::where('id',$id)->first();
         $response['hotel_category'] = HotelCategory::get();
         $response['dzongkhag'] = Dzongkhag::get();
         if (@$response['data']->dzongkhag_id!="") {
            $response['hotels'] = HotelSeparate::where('dzongkhag_id',$response['data']->dzongkhag_id)->get(); 
            $response['pois'] = Poi::where('dzongkhag',$response['data']->dzongkhag_id)->get();
         }
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
            'last_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'role' => 'required',
            'id'=>'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $check = User::where('email',$request->email)->where('id','!=',$request->id)->first();
        if (@$check!="") {
            $response['success'] = false;
            $response['message'] = 'Email Already Added.Please Try Another One';
        }

        $ins = [];
        $ins['name'] = $request->name;
        $ins['middle_name'] = $request->middle_name;
        $ins['last_name'] = $request->last_name;
        $ins['document_no'] = $request->document_no;
        $ins['phone'] = $request->phone;
        $ins['role'] = $request->role;
        $ins['dzongkhag_id'] = $request->dzongkhag_id;
        $ins['hotel_category'] = $request->hotel_category;
        $ins['hotel_id'] = $request->hotel_id;
        $ins['email'] = $request->email;
        $ins['poi_id'] = $request->poi_id;
        User::where('id',$request->id)->update($ins);
        $response['success'] = true;
        $response['message'] = 'User Updated Successfully';
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
         $response['success'] = true;   
         User::where('id',$id)->update(['status'=>'D']);
         $response['message'] = 'User Deleted Successfully';
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function status($id)
    {
        $response = [];
        try{
         $response['success'] = true;
         $data = User::where('id',$id)->first();   
         if (@$data->status=="A") {
             User::where('id',$id)->update(['status'=>'I']);
             $response['message'] = 'Status Changed Successfully';
         }else{
            User::where('id',$id)->update(['status'=>'A']);
            $response['message'] = 'Status Changed Successfully';
         }
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function getPoi(Request $request)
    {
        $response = [];
        try{
         $response['success'] = true;   
         $response['pois'] = Poi::where('dzongkhag',$request->dzongkhag_id)->get();
         $response['hotels'] = HotelSeparate::where('dzongkhag_id',$request->dzongkhag_id)->get();
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function getHotel(Request $request)
    {
        $response = [];
        try{
         $response['success'] = true;   
         $response['hotels'] = HotelSeparate::where('category_id',$request->hotel_category)->get();
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }

}
