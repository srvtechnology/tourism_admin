<?php

namespace App\Http\Controllers\Api\Airline;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use App\Models\User;
use Response;

use Illuminate\Http\Request;
use App\Models\Airlines;
class AirlineController extends Controller
{
    

    public function add(Request $request)
 	{
 		$response = [];
    	try{
    	//valid credential
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'name' => 'required',
            'country'=>'required',
            'image'=>'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['code'] = $request->code;
        $ins['name'] = $request->name;
        $ins['country'] = $request->country;
        if ($request->hasFile('image'))
        {
             $image = $request->image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/airline",$filename);
             $ins['image'] = $filename;
        }
        Airlines::create($ins);
        $response['success'] = true;	
        $response['message'] = 'Airline Inserted Successfully';
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
         $airlines = Airlines::where('status','!=','D')->get();
         $response['success'] = true;
         $response['airlines'] = $airlines;   
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
         $check = Airlines::where('id',$id)->first();
         if (@$check->status=="A") {
         	Airlines::where('id',$id)->update(['status'=>'I']);
         	$response['success'] = true;
         	$response['message'] = 'Status Deactivated Successfully';
         	return Response::json($response);
         }else{
         	Airlines::where('id',$id)->update(['status'=>'A']);
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
        	Airlines::where('id',$id)->update(['status'=>'D']);
         	$response['success'] = true;
         	$response['message'] = 'Airlines Deleted Successfully';
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
        	$airline = Airlines::where('id',$id)->first();
         	$response['success'] = true;
         	$response['airline'] = $airline;
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
            'code' => 'required',
            'name' => 'required',
            'country'=>'required',
            // 'image'=>'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $img = Airlines::where('id',$request->id)->first();
        $ins = [];
        $ins['code'] = $request->code;
        $ins['name'] = $request->name;
        $ins['country'] = $request->country;
         if ($request->hasFile('image'))
        {
             @unlink('storage/app/public/airline/'.$img->image);
             $image = $request->image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/airline",$filename);
             $upd['image'] = $filename;
        }
        Airlines::where('id',$request->id)->update($upd);
        $response['success'] = true;	
        $response['message'] = 'Airline Updated Successfully';
        return Response::json($response);

    	
    	}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
 	}
}
