<?php

namespace App\Http\Controllers\Api\Tour;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use App\Models\User;
use Response;
use App\Models\TourCategory;


class TourCategoryController extends Controller
{
	public function add(Request $request)
	{

	  $response = [];
    	try{
    	//valid credential
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['name'] = $request->name;
        $ins['description'] = $request->description;
        TourCategory::create($ins);
        $response['success'] = true;	
        $response['message'] = 'Tour Category Created Successfully';
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
         $categories = TourCategory::where('status','!=','D')->get();
         $response['success'] = true;
         $response['categories'] = $categories;   
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
         $check = TourCategory::where('id',$id)->first();
         if (@$check->status=="A") {
         	TourCategory::where('id',$id)->update(['status'=>'I']);
         	$response['success'] = true;
         	$response['message'] = 'Status Deactivated Successfully';
         	return Response::json($response);
         }else{
         	TourCategory::where('id',$id)->update(['status'=>'A']);
         	$response['success'] = true;
         	$response['message'] = 'Status Activated Successfully';
         	return Response::json($response);
         }	
        

        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }  
    }

    public function delete($id)
    {
    	$response = [];
        try{
        	TourCategory::where('id',$id)->update(['status'=>'D']);
         	$response['success'] = true;
         	$response['message'] = 'Tour Category Deleted Successfully';
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
        	$category = TourCategory::where('id',$id)->first();
         	$response['success'] = true;
         	$response['category'] = $category;
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
            'description' => 'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $upd = [];
        $upd['name'] = $request->name;
        $upd['description'] = $request->description;
        TourCategory::where('id',$request->id)->update($upd);
        $response['success'] = true;	
        $response['message'] = 'Tour Category Updated Successfully';
        return Response::json($response);

    	
    	}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }

    }



}
