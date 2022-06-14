<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rooms;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use App\Models\User;
use Response;
use App\Models\Hotel;
use App\Models\Amenities;
use App\Models\RoomToAmenities;
use App\Models\Extras;
class RoomController extends Controller
{

    public function roomAddView()
    {   
        $response = [];
        try{
        $hotels = Hotel::where('status','!=','D')->select('id','name')->get();
        $response['success'] = true;
       $response['hotels_list'] = $hotels;
        return Response::json($response);   
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }

    public function roomAdd(Request $request)
    {
    	$response = [];
    	try{

        //valid credential
        $validator = Validator::make($request->all(), [
            'hotel_id' => 'required',
            'type' => 'required',
            'description' => 'required',
            'status' => 'required',
            'price_type' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'min_stay' => 'required',
            'max_adults' => 'required',
            'max_child' => 'required',
            'no_of_extra_bed' => 'required',
            'extra_bed_chages' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['hotel_id'] = $request->hotel_id;
        $ins['type'] = $request->type;
        $ins['description'] = $request->description;
        $ins['status'] = $request->status;
        $ins['price_type'] = $request->price_type;
        $ins['price'] = $request->price;
    	$ins['quantity'] = $request->quantity;
    	$ins['min_stay'] = $request->min_stay;
    	$ins['max_adults'] = $request->max_adults;
    	$ins['max_child'] = $request->max_child;
    	$ins['no_of_extra_bed'] = $request->no_of_extra_bed;
    	$ins['extra_bed_chages'] = $request->extra_bed_chages;
    	Rooms::create($ins);
    	$response['success'] = true;	
        $response['message'] = 'Room Inserted Successfully';
        return Response::json($response);
    	}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }		
    }


    public function roomListing()
    {
    	$response = [];
    	try{
        $rooms =  Rooms::where('status','!=','DL') ->with(['hotel_name' => function ($query) {
        $query->select('id', 'name');
        }])->get();

        $response['success'] = true;
        $response['rooms'] = $rooms;
    	return Response::json($response); 		
    	 	
		}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function roomDelete($id)
    {
    	$response = [];
    	try{
    	// DL MEANS DELETE	
        $delete = Rooms::where('id',$id)->update(['status'=>'DL']);		
        $response['success'] = true;
        $response['message'] = 'Rooms Deleted Successfully';
        return Response::json($response); 
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }		
    }

    public function roomStatus($id)
    {
        $response = [];
        try{
         $hotel = Rooms::where('id',$id)->first();
         if (@$hotel->status=="E") {
            Rooms::where('id',$id)->update(['status'=>'D']);
            $response['success'] = true;
            $response['message'] = 'Room Disabled Successfully';
            return Response::json($response);
         }else{
            Rooms::where('id',$id)->update(['status'=>'E']);
            $response['success'] = true;
            $response['message'] = 'Room Enabled Successfully';
            return Response::json($response);
         }
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }   
    }


    public function roomEdit($id)
    {
    	$response = [];
    	try{
    	$room = Rooms::where('id',$id)->first();
    	$hotels = Hotel::where('status','!=','D')->select('id','name')->get();
    	$response['success'] = true;
    	$response['room'] = $room;	
    	$response['hotels_list'] = $hotels;
    	return Response::json($response); 	
    	}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }		
    }


    public function roomUpdate(Request $request)
    {
       $response = [];
    	try{

        //valid credential
        $validator = Validator::make($request->all(), [
            'hotel_id' => 'required',
            'type' => 'required',
            'description' => 'required',
            'status' => 'required',
            'price_type' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'min_stay' => 'required',
            'max_adults' => 'required',
            'max_child' => 'required',
            'no_of_extra_bed' => 'required',
            'extra_bed_chages' => 'required',
            'id'=>'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $upd = [];
        $upd['hotel_id'] = $request->hotel_id;
        $upd['type'] = $request->type;
        $upd['description'] = $request->description;
        $upd['status'] = $request->status;
        $upd['price_type'] = $request->price_type;
        $upd['price'] = $request->price;
    	$upd['quantity'] = $request->quantity;
    	$upd['min_stay'] = $request->min_stay;
    	$upd['max_adults'] = $request->max_adults;
    	$upd['max_child'] = $request->max_child;
    	$upd['no_of_extra_bed'] = $request->no_of_extra_bed;
    	$upd['extra_bed_chages'] = $request->extra_bed_chages;
    	Rooms::where('id',$request->id)->update($upd);
    	$response['success'] = true;	
        $response['message'] = 'Room Updated Successfully';
        return Response::json($response);
    	
    	}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }	
    }


    public function roomAmenities($id)
    {
    	$response = [];
    	try{
    	$amenities = Amenities::get();
    	$selected_amenities = RoomToAmenities::where('room_id',$id)->get();
    	$response['success'] = true;
    	$response['amenities'] = $amenities;	
    	$response['selected_amenities'] = $selected_amenities;
    	return Response::json($response); 	
    	}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }	
    }


    public function roomUpdateAmenities(Request $request)
    {
    	$response = [];
    	try{
    	
        
        if (@$request->amenities) {
            $explode = explode(',', $request->id);
            RoomToAmenities::whereIn('room_id',$explode)->delete();
            foreach (@$request->amenities as $key => $value){
            $ins = [];
            $ins['room_id'] = $request->id;
            $ins['amenity_id'] = $value;
            RoomToAmenities::create($ins);
          }
        }	
        $response['success'] = true;
    	$response['message'] = 'Data Updated Successfully';
    	return Response::json($response);	
        

        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }			
    }



    public function roomExtrasAdd(Request $request)
    {
    	$response = [];
    	try{

        //valid credential
        $validator = Validator::make($request->all(),[
            'room_id' => 'required',
            'name' => 'required',
            'image' => 'required',
            'availability' => 'required',
            'price' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $ins = [];
        $ins['room_id'] = $request->room_id;
        $ins['name'] = $request->name;
        $ins['availability'] = $request->availability;
        $ins['price'] = $request->price;
        if ($request->hasFile('image'))
        {
             $image = $request->image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/extras",$filename);
             $ins['image'] = $filename;
        }
        Extras::create($ins);
        $response['success'] = true;
    	$response['message'] = 'Data Inserted Successfully';
    	return Response::json($response);

        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }	
    }


    public function roomExtrasLisiting($id)
    {
    	$response = [];
    	try{
    	  $extras = Extras::where('status','!=','D')->get();
    	  $response['success'] = true;
    	  $response['extras'] = $extras;
    	  return Response::json($response);
    	}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }	
    }


    public function roomExtrasDelete($id)
    {
        $response = [];
        try{
        Extras::where('id',$id)->update(['status'=>'D']);
        $response['success'] = true;
        $response['message'] = 'Data Deleted Successfully';
        return Response::json($response);
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }

    }

    public function roomExtrasStatus($id)
    {
       $response = [];
       try{ 
        $check = Extras::where('id',$id)->first();
        if (@$check->status=="A") {
            Extras::where('id',$id)->update(['status'=>'I']);
            $response['success'] = true;
            $response['message'] = 'Data Deactivated Successfully';
            return Response::json($response);
        }else{
            Extras::where('id',$id)->update(['status'=>'A']);
            $response['success'] = true;
            $response['message'] = 'Data Activated Successfully';
            return Response::json($response);
        }
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function roomExtrasEdit($id)
    {
       $response = [];
       try{ 
        $extra = Extras::where('id',$id)->first();
        $response['success'] = true;
        $response['extra'] = $extra;
        return Response::json($response);
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function roomExtrasUpdate(Request $request)
    {
        $response = [];
        try{

        //valid credential
        $validator = Validator::make($request->all(),[
            
            'name' => 'required',
            // 'image' => 'required',
            'availability' => 'required',
            'price' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $data = Extras::where('id',$request->id)->first();
        $upd = [];
        
        $upd['name'] = $request->name;
        $upd['availability'] = $request->availability;
        $upd['price'] = $request->price;
        if ($request->hasFile('image'))
        {
            @unlink('storage/app/public/extras/'.$data->image);
             $image = $request->image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/extras",$filename);
             $upd['image'] = $filename;
        }
        Extras::where('id',$request->id)->update($upd);
        $response['success'] = true;
        $response['message'] = 'Data Updated Successfully';
        return Response::json($response);

        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }   
    }



}
