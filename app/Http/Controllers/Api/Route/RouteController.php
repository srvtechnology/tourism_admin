<?php

namespace App\Http\Controllers\Api\Route;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Airlines;
use App\Models\Airports;
use App\Models\Routes;

use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use App\Models\User;
use App\Models\RouteToTransit;
use Response;
class RouteController extends Controller
{
   
    public function addView()
    {
    	$response = [];
        try{
         $response['airlines'] = Airlines::select('id','name')->get();
         $response['airports'] = Airports::select('id','name')->get();
         $response['success'] = true;
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
            'departure_airport_id' => 'required',
            'departure_airline_id' => 'required',
            'departure_flight_no' => 'required',
            'departure_flight_time' => 'required',


            'arrival_airport_id' => 'required',
            'arrival_airline_id' => 'required',
            'arrival_flight_no' => 'required',
            'arrival_flight_time' => 'required',


            'bagage_kgs' => 'required',
            'total_hours' => 'required',
            'vat_tax' => 'required',
            'deposit' => 'required',


            'class' => 'required',
            'refundable' => 'required',
            'description' => 'required',
            'status' => 'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['departure_airport_id'] = $request->departure_airport_id;
        $ins['departure_airline_id'] = $request->departure_airline_id;
        $ins['departure_flight_no'] = $request->departure_flight_no;
        $ins['departure_flight_time'] = $request->departure_flight_time;


        $ins['arrival_airport_id'] = $request->arrival_airport_id;
        $ins['arrival_airline_id'] = $request->arrival_airline_id;
        $ins['arrival_flight_no'] = $request->arrival_flight_no;
        $ins['arrival_flight_time'] = $request->arrival_flight_time;

        $ins['bagage_kgs'] = $request->bagage_kgs;
        $ins['total_hours'] = $request->total_hours;
        $ins['vat_tax'] = $request->vat_tax;
        $ins['deposit'] = $request->deposit;

        $ins['class'] = $request->class;
        $ins['refundable'] = $request->refundable;
        $ins['description'] = $request->description;
        $ins['status'] = $request->status;
        



        $route = Routes::create($ins);

        if (@$request->transit) {

          foreach ($request->transit as $key => $value) {

          RouteToTransit::create([
              'route_id' =>$route->id,
              'airport_id' =>$value['airport_id'],
              'airline_id' => $value['airline_id'],
              'flight_no' => $value['flight_no'],
              'flight_time' => $value['flight_time'],
          ]);
          //  for($i=0;$i<count(@$request->transit_airport_id);$i++)
          // {
          //     RouteToTransit::create([
          //       'route_id' =>$route->id,
          //       'airport_id' => @$request->transit_airport_id[$i],
          //       'airline_id' => @$request->transit_airline_id[$i],
          //       'flight_no' =>  @$request->transit_flight_no[$i], 
          //       'flight_time' =>  @$request->transit_flight_time[$i], 
          //   ]);
          // }
        }
      }

        


        $response['success'] = true;	
        $response['message'] = 'Route Created Successfully';
        return Response::json($response);

    	
    	}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function listing()
    {
    	// return 'sayan';
    	$response = [];
        try{
         $response['routes'] = Routes::with('departure_airport','arrival_airport','departure_airline','arrival_airline')->where('status','!=','DE')->get();
         $response['success'] = true;
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
        	Routes::where('id',$id)->update(['status'=>'DE']);
         	$response['success'] = true;
         	$response['message'] = 'Route Deleted Successfully';
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
         $check = Routes::where('id',$id)->first();
         if (@$check->status=="E") {
         	Routes::where('id',$id)->update(['status'=>'D']);
         	$response['success'] = true;
         	$response['message'] = 'Status Disabled Successfully';
         	return Response::json($response);
         }else{
         	Routes::where('id',$id)->update(['status'=>'E']);
         	$response['success'] = true;
         	$response['message'] = 'Status Enabled Successfully';
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
         $response['route'] = Routes::where('id',$id)->first();   
         $response['airlines'] = Airlines::select('id','name')->get();
         $response['airports'] = Airports::select('id','name')->get();
         $response['transit'] = RouteToTransit::where('route_id',$id)->where('airport_id','!=',null)->get();
         $response['success'] = true;
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
            'departure_airport_id' => 'required',
            'departure_airline_id' => 'required',
            'departure_flight_no' => 'required',
            'departure_flight_time' => 'required',


            'arrival_airport_id' => 'required',
            'arrival_airline_id' => 'required',
            'arrival_flight_no' => 'required',
            'arrival_flight_time' => 'required',


            'bagage_kgs' => 'required',
            'total_hours' => 'required',
            'vat_tax' => 'required',
            'deposit' => 'required',


            'class' => 'required',
            'refundable' => 'required',
            'description' => 'required',
            'status' => 'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $upd = [];
        $upd['departure_airport_id'] = $request->departure_airport_id;
        $upd['departure_airline_id'] = $request->departure_airline_id;
        $upd['departure_flight_no'] = $request->departure_flight_no;
        $upd['departure_flight_time'] = $request->departure_flight_time;


        $upd['arrival_airport_id'] = $request->arrival_airport_id;
        $upd['arrival_airline_id'] = $request->arrival_airline_id;
        $upd['arrival_flight_no'] = $request->arrival_flight_no;
        $upd['arrival_flight_time'] = $request->arrival_flight_time;

        $upd['bagage_kgs'] = $request->bagage_kgs;
        $upd['total_hours'] = $request->total_hours;
        $upd['vat_tax'] = $request->vat_tax;
        $upd['deposit'] = $request->deposit;

        $upd['class'] = $request->class;
        $upd['refundable'] = $request->refundable;
        $upd['description'] = $request->description;
        $upd['status'] = $request->status;
        
       //  if (@$request->transit) {
       //  RouteToTransit::where('route_id',$request->id)->delete();
        
       //  for($i=0;$i<count(@$request->transit_airport_id);$i++)
       //  {
       //      RouteToTransit::create([
       //        'route_id' =>$request->id,
       //        'airport_id' => @$request->transit_airport_id[$i],
       //        'airline_id' => @$request->transit_airline_id[$i],
       //        'flight_no' =>  @$request->transit_flight_no[$i], 
       //        'flight_time' =>  @$request->transit_flight_time[$i], 
       //    ]);
       //  }

       // }
       RouteToTransit::where('route_id',$request->id)->delete();
       if (@$request->transit) {
          
          foreach ($request->transit as $key => $value) {

          RouteToTransit::create([
              'route_id' =>$request->id,
              'airport_id' =>$value['airport_id'],
              'airline_id' => $value['airline_id'],
              'flight_no' => $value['flight_no'],
              'flight_time' => $value['flight_time'],
          ]);
          
        }
      }



        Routes::where('id',$request->id)->update($upd);
        $response['success'] = true;    
        $response['message'] = 'Route Updated Successfully';
        return Response::json($response);

        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
    }


    public function view($id)
    {
      $response = [];
        try{
         $response['route'] = Routes::with('departure_airport','arrival_airport','departure_airline','arrival_airline')->where('id',$id)->first();   
         // $response['airlines'] = Airlines::select('id','name')->get();
         // $response['airports'] = Airports::select('id','name')->get();
         $response['transits'] = RouteToTransit::where('route_id',$id)->with('airport_name','airline_name')->get();
         $response['success'] = true;
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
    }
}
