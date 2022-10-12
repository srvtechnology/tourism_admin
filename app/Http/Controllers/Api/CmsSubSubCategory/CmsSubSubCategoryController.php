<?php

namespace App\Http\Controllers\Api\CmsSubSubCategory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Response;
use App\Models\CmsSubSubCategory;
use App\Models\CmsSubCategory;
use App\Models\CmsCategory;
class CmsSubSubCategoryController extends Controller
{
    public function getCmsSubCategory(Request $request)
    {
        $response = [];
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'id'=>'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $data = CmsSubCategory::where('category_id',$request->id)->where('status','!=','D')->get();
        $response['data'] = $data;
        $response['success'] = true;
        return Response::json($response);

        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }

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
            'sub_category_id'=>'required'
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['name'] = $request->name;
        $ins['category_id'] = $request->category_id;
        $ins['sub_category_id'] = $request->sub_category_id;
        CmsSubSubCategory::create($ins);
        $response['success'] = true;    
        $response['message'] = 'Cms Sub Sub Category Created Successfully';
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
         $categories = CmsSubSubCategory::with('category_name')->with('sub_category_name')->where('status','!=','D')->get();
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
            CmsSubSubCategory::where('id',$id)->update(['status'=>'D']);
            $response['success'] = true;
            $response['message'] = 'Cms Sub Sub Category Deleted Successfully';
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
            $response['data'] = CmsSubSubCategory::where('id',$id)->first();
            $response['sub_categories'] = CmsSubCategory::where('category_id',$response['data']->category_id)->where('status','!=','D')->get();
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
            'sub_category_id'=>'required',
            'id'=>'required'
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['name'] = $request->name;
        $ins['category_id'] = $request->category_id;
        $ins['sub_category_id'] = $request->sub_category_id;
        CmsSubSubCategory::where('id',$request->id)->update($ins);
        $response['success'] = true;    
        $response['message'] = 'Cms Sub Sub Category Updated Successfully';
        return Response::json($response);

        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }
}
