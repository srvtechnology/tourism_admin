<?php

namespace App\Http\Controllers\Api\Header;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Response;
use App\Models\HeaderImageModel;
class HeaderImage extends Controller
{
    public function add(Request $request)
    {
        $response = [];
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'image' => 'required',
            'text'=>'required',
            'name' => 'required',
        ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['name'] = $request->name;
        $ins['text'] = $request->text;
        if ($request->hasFile('image'))
        {
             $image = $request->image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/header_image",$filename);
             $ins['image'] = $filename;
        }

        HeaderImageModel::create($ins);
        $response['success'] = true;
        $response['message'] = 'Image inserted Successfully';

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
         $images = HeaderImageModel::get();
         $response['success'] = true;
         $response['images'] = $images;  
         $response['image_link'] = 'http://services.tourism.gov.bt/storage/app/public/header_image/'; 
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
         $image = HeaderImageModel::where('id',$id)->first();
         $response['success'] = true;
         $response['image'] = $image;  
         $response['image_link'] = 'http://services.tourism.gov.bt/storage/app/public/header_image/'; 
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
            'id' => 'required',
            'text'=>'required',
            'name' => 'required',
        ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['name'] = $request->name;
        $ins['text'] = $request->text;
        if ($request->hasFile('image'))
        {
             $image = $request->image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/header_image",$filename);
             $ins['image'] = $filename;
        }

        HeaderImageModel::where('id',$request->id)->update($ins);
        $response['success'] = true;
        $response['message'] = 'Image Updated Successfully';

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
            HeaderImageModel::where('id',$id)->delete();
            $response['success'] = true;
            $response['message'] = 'Image Deleted Successfully';
            return Response::json($response);
        }catch(\Exception $e){
                $response['error'] = $e->getMessage();
                return Response::json($response);
        }
    }
}
