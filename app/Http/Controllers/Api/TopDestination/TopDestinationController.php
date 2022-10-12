<?php

namespace App\Http\Controllers\Api\TopDestination;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TopDestination;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use App\Models\User;
use Response;
class TopDestinationController extends Controller
{
    public function add(Request $request)
    {
        $response = [];
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'image'=>'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['title'] = $request->title;
        if ($request->hasFile('image'))
        {
             $image = $request->image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/top_destination",$filename);
             $ins['image'] = $filename;
        }
        TopDestination::create($ins);
        $response['success'] = true;    
        $response['message'] = 'Top Destination Added Successfully';
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
         $data = TopDestination::get();
         $response['success'] = true;
         $response['data'] = $data;   
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
         $data = TopDestination::where('id',$id)->first();
         $response['success'] = true;
         $response['data'] = $data;   
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
            'title' => 'required',
            'id'=>'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $img = TopDestination::where('id',$request->id)->first();

        $upd = [];
        $upd['title'] = $request->title;
        if ($request->hasFile('image'))
        {
             @unlink('storage/app/public/top_destination/'.$img->image);
             $image = $request->image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/top_destination",$filename);
             $upd['image'] = $filename;
        }
        TopDestination::where('id',$request->id)->update($upd);
        $response['success'] = true;    
        $response['message'] = 'Top Destination Updated Successfully';
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
            $img = TopDestination::where('id',$id)->first();
            @unlink('storage/app/public/top_destination/'.$img->image);
            TopDestination::where('id',$id)->delete();
            $response['success'] = true;
            $response['message'] = 'Top Destination Deleted Successfully';
            return Response::json($response);
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
     }

}