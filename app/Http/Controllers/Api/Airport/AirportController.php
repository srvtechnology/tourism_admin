<?php

namespace App\Http\Controllers\Api\Airport;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use App\Models\User;
use Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Airports;
class AirportController extends Controller
{
 	
 	public function add(Request $request)
 	{
 		$response = [];
    	try{
    	//valid credential
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'name' => 'required',
            'citycode' => 'required',
            'cityname' => 'required',
            'countryname' => 'required',
            'countrycode' => 'required',
            'continent_id' => 'required',
            'timezone' => 'required',
            'lat' => 'required',
            'lon' => 'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['code'] = $request->code;
        $ins['name'] = $request->name;
        $ins['citycode'] = $request->citycode;
        $ins['cityname'] = $request->cityname;
        $ins['countryname'] = $request->countryname;
        $ins['countrycode'] = $request->countrycode;
        $ins['continent_id'] = $request->continent_id;
        $ins['timezone'] = $request->timezone;
        $ins['lat'] = $request->lat;
        $ins['lon'] = $request->lon;
        Airports::create($ins);
        $response['success'] = true;	
        $response['message'] = 'Airport Inserted Successfully';
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
         $airports = Airports::where('status','!=','D')->get();
         $response['success'] = true;
         $response['airports'] = $airports;   
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
         $check = Airports::where('id',$id)->first();
         if (@$check->status=="A") {
         	Airports::where('id',$id)->update(['status'=>'I']);
         	$response['success'] = true;
         	$response['message'] = 'Status Deactivated Successfully';
         	return Response::json($response);
         }else{
         	Airports::where('id',$id)->update(['status'=>'A']);
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
        	Airports::where('id',$id)->update(['status'=>'D']);
         	$response['success'] = true;
         	$response['message'] = 'Airport Deleted Successfully';
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
        	$airport = Airports::where('id',$id)->first();
         	$response['success'] = true;
         	$response['airport'] = $airport;
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
            'citycode' => 'required',
            'cityname' => 'required',
            'countryname' => 'required',
            'countrycode' => 'required',
            'continent_id' => 'required',
            'timezone' => 'required',
            'lat' => 'required',
            'lon' => 'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $upd = [];
        $upd['code'] = $request->code;
        $upd['name'] = $request->name;
        $upd['citycode'] = $request->citycode;
        $upd['cityname'] = $request->cityname;
        $upd['countryname'] = $request->countryname;
        $upd['countrycode'] = $request->countrycode;
        $upd['continent_id'] = $request->continent_id;
        $upd['timezone'] = $request->timezone;
        $upd['lat'] = $request->lat;
        $upd['lon'] = $request->lon;
        Airports::where('id',$request->id)->update($upd);
        $response['success'] = true;	
        $response['message'] = 'Airport Updated Successfully';
        return Response::json($response);

    	
    	}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
 	}
}
