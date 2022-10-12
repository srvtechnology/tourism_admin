<?php

namespace App\Http\Controllers\Api\Flight;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use App\Models\User;
use Response;
use App\Models\FeaturedFlight;

class FlightController extends Controller
{
    public function add(Request $request)
    {
       $response = [];
    	try{
    	//valid credential
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'from_destination' => 'required',
            'to_destination'=>'required',
            'price'=>'required',
            'thumbnail'=>'required',
            'status'=>'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['title'] = $request->title;
        $ins['from_destination'] = $request->from_destination;
        $ins['to_destination'] = $request->to_destination;
        $ins['price'] = $request->price;
        $ins['status'] = $request->status;
        if ($request->hasFile('thumbnail'))
        {
             $image = $request->thumbnail;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/featured",$filename);
             $ins['thumbnail'] = $filename;
        }
        FeaturedFlight::create($ins);
        $response['success'] = true;	
        $response['message'] = 'Featured Flight Inserted Successfully';
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
         $featured_flights = FeaturedFlight::where('status','!=','D')->get();
         $response['success'] = true;
         $response['flights'] = $featured_flights;   
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
        	FeaturedFlight::where('id',$id)->update(['status'=>'D']);
         	$response['success'] = true;
         	$response['message'] = 'Featured Flight Deleted Successfully';
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
         $check = FeaturedFlight::where('id',$id)->first();
         if (@$check->status=="A") {
            FeaturedFlight::where('id',$id)->update(['status'=>'I']);
            $response['success'] = true;
            $response['message'] = 'Status Deactivated Successfully';
            return Response::json($response);
         }else{
            FeaturedFlight::where('id',$id)->update(['status'=>'A']);
            $response['success'] = true;
            $response['message'] = 'Status Activated Successfully';
            return Response::json($response);
         }  
        

        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function edit($id)
    {
    	$response = [];
        try{
        	$flight = FeaturedFlight::where('id',$id)->first();
         	$response['success'] = true;
         	$response['flight'] = $flight;
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
            'from_destination' => 'required',
            'to_destination'=>'required',
            'price'=>'required',
            // 'thumbnail'=>'required',
            'status'=>'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $img = FeaturedFlight::where('id',$request->id)->first();
        $upd = [];
        $upd['title'] = $request->title;
        $upd['from_destination'] = $request->from_destination;
        $upd['to_destination'] = $request->to_destination;
        $upd['price'] = $request->price;
        $upd['status'] = $request->status;
        if ($request->hasFile('thumbnail'))
        {
        	@unlink('storage/app/public/featured/'.$img->thumbnail);
             $image = $request->thumbnail;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/featured",$filename);
             $upd['thumbnail'] = $filename;
        }
        FeaturedFlight::where('id',$request->id)->update($upd);
        $response['success'] = true;	
        $response['message'] = 'Featured Flight Updated Successfully';
        return Response::json($response);

    	
    	}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }
}
