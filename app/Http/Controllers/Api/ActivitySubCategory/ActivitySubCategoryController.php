<?php

namespace App\Http\Controllers\Api\ActivitySubCategory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityCategory;
use App\Models\ActivitySubCategory;
class ActivitySubCategoryController extends Controller
{
    public function addView()
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

    public function add(Request $request)
    {
        $response = [];
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'category_id'=>'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['name'] = $request->name;
        $ins['description'] = $request->description;
        $ins['category_id'] = $request->category_id;
        ActivitySubCategory::create($ins);
        $response['success'] = true;    
        $response['message'] = 'Activity Sub Category Created Successfully';
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
         $categories = ActivitySubCategory::with('category_name')->where('status','!=','D')->get();
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
            ActivitySubCategory::where('id',$id)->update(['status'=>'D']);
            $response['success'] = true;
            $response['message'] = 'Activity Sub Category Deleted Successfully';
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
            $categories = ActivityCategory::where('status','!=','D')->get();
            $response['success'] = true;
            $response['categories'] = $categories;
            $response['data'] = ActivitySubCategory::where('id',$id)->first();
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
            'category_id'=>'required',
            'id'=>'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['name'] = $request->name;
        $ins['description'] = $request->description;
        $ins['category_id'] = $request->category_id;
        ActivitySubCategory::where('id',$request->id)->update($ins);
        $response['success'] = true;    
        $response['message'] = 'Activity Sub Category Updated Successfully';
        return Response::json($response);

        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }
}
