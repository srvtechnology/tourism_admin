<?php

namespace App\Http\Controllers\Api\CmsSubCategory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsCategory;
use App\Models\CmsSubCategory;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Response;
class CmsSubCategoryController extends Controller
{
    public function addView()
    {
        $response = [];
        try{
         $categories = CmsCategory::where('status','!=','D')->get();
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
            'category_id'=>'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['name'] = $request->name;
        $ins['category_id'] = $request->category_id;
        CmsSubCategory::create($ins);
        $response['success'] = true;    
        $response['message'] = 'Cms Sub Category Created Successfully';
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
         $categories = CmsSubCategory::with('category_name')->where('status','!=','D')->get();
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
            CmsSubCategory::where('id',$id)->update(['status'=>'D']);
            $response['success'] = true;
            $response['message'] = 'Cms Sub Category Deleted Successfully';
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
            $categories = CmsCategory::where('status','!=','D')->get();
            $response['success'] = true;
            $response['categories'] = $categories;
            $response['data'] = CmsSubCategory::where('id',$id)->first();
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
            'category_id'=>'required',
            'id'=>'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['name'] = $request->name;
        $ins['category_id'] = $request->category_id;
        CmsSubCategory::where('id',$request->id)->update($ins);
        $response['success'] = true;    
        $response['message'] = 'Cms Sub Category Updated Successfully';
        return Response::json($response);

        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }
}

