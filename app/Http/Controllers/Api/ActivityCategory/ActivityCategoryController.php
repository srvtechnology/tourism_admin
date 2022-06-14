<?php

namespace App\Http\Controllers\Api\ActivityCategory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Response;
use App\Models\ActivityCategory;
class ActivityCategoryController extends Controller
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
        ActivityCategory::create($ins);
        $response['success'] = true;    
        $response['message'] = 'Activity Category Created Successfully';
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
         $categories = ActivityCategory::where('status','!=','D')->get();
         $response['success'] = true;
         $response['categories'] = $categories;   
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
            ActivityCategory::where('id',$id)->update(['status'=>'D']);
            $response['success'] = true;
            $response['message'] = 'Activity Category Deleted Successfully';
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
            $category = ActivityCategory::where('id',$id)->first();
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
        ActivityCategory::where('id',$request->id)->update($upd);
        $response['success'] = true;    
        $response['message'] = 'Activity Category Updated Successfully';
        return Response::json($response);

        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }

    }
}
