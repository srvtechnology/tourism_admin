<?php

namespace App\Http\Controllers\Api\Hotel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\Gewog;
use App\Models\Dzongkhag;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use App\Models\User;
use App\Models\Destination;
use App\Models\HotelToDestination;
use Response;
use App\Models\HotelToFacilities;
use App\Models\Facilities;
use App\Models\HotelMetaInformation;
use App\Models\HotelPolicy;
use App\Models\HotelContact;
use App\Models\HotelToImages;
use App\Models\HotelToVideos;
use App\Models\Payments;
use App\Models\HotelPolicyPayment;
class HotelController extends Controller
{
    
	public function addView()
	{
		$response = [];
    	try{
    	  $gewog = Gewog::select('id','name')->get();
    	  $dzongkhag = Dzongkhag::select('id','name')->get();
    	  $destination = Destination::select('id','name')->get();
    	  $response['success'] = true;	
    	  $response['gewogdata'] = $gewog;
    	  $response['dzongkhagdata'] = $dzongkhag;
    	  $response['destinationdata'] = $destination;
    	  return Response::json($response);
    	 	
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }	
	}


	public function insertHotel(Request $request)
	{
		$response = [];
    	try{
    	//valid credential
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'dzongkhag_id' => 'required',
            'gewog_id' => 'required',
            'status' => 'required',
            'stars' => 'required',
            'hotel_type' => 'required',
            'featured' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            // 'latitude' => 'required',
            'deposit_type'=>'required',
            'tax_type'=>'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['name'] = $request->name;
        $ins['description'] = $request->description;
        $ins['dzongkhag_id'] = $request->dzongkhag_id;
        $ins['gewog_id'] = $request->gewog_id;
        $ins['status'] = $request->status;
        $ins['stars'] = $request->stars;
        $ins['hotel_type'] = $request->hotel_type;
        $ins['featured'] = $request->featured;
        $ins['from_date'] = $request->from_date;
        $ins['to_date'] = $request->to_date;
        $ins['longitude'] = $request->longitude;
        $ins['latitude'] = $request->latitude;
        $ins['b2c_discount'] = $request->b2c_discount;
        $ins['b2b_discount'] = $request->b2b_discount;
        $ins['b2e_discount'] = $request->b2e_discount;
        $ins['corporate_discount'] = $request->corporate_discount;
        $ins['service_fees'] = $request->service_fees;
        $ins['deposit_type'] = $request->deposit_type;
        $ins['deposit_value'] = $request->deposit_value;
        $ins['tax_type'] = $request->tax_type;
        $ins['tax_value'] = $request->tax_value;


        

        $hotel_save = Hotel::create($ins);
        
        if ($request->accessible_destination) {
        $accessible_destinations = $request->accessible_destination;
        foreach ($accessible_destinations as $value) {
            $insdes = [];
            $insdes['hotel_id'] = $hotel_save->id;
            $insdes['destination_id'] = $value;
            HotelToDestination::create($insdes);
          }
        }
        
        $response['success'] = true;	
        $response['message'] = 'Hotel Inserted Successfully';
        return Response::json($response);



    	}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }	
	}

	public function listingHotel()
	{
		$response = [];
    	try{
    	 $hotel = Hotel::where('hotel_status','!=','D')->with('gewog','dzongkhag','selectedDestination')->get();
    	 $response['success'] = true;	
    	 $response['data'] = $hotel;	
    	 return Response::json($response);
    	
    	}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }	
	}

	public function viewHotel($id)
	{
		$response = [];
    	try{
    	  $hotel = Hotel::where('status','!=','D')->where('id',$id)->with('gewog','dzongkhag','selectedDestination.destination_name')->first();
    	  $gewog = Gewog::select('id','name')->get();
    	  $dzongkhag = Dzongkhag::select('id','name')->get();
    	  $destination = Destination::select('id','name')->get();
    	  $response['success'] = true;	
    	  $response['data'] = $hotel;
    	  $response['gewogdata'] = $gewog;
    	  $response['dzongkhagdata'] = $dzongkhag;
    	  $response['destinationdata'] = $destination;
    	  return Response::json($response);

        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }		
     }


     public function statusHotel($id)
     {
     	$response = [];
    	try{
    	 $hotel = Hotel::where('status','!=','D')->where('id',$id)->first();
    	 if (@$hotel->hotel_status=="A") {
    	 	Hotel::where('status','!=','D')->where('id',$id)->update(['hotel_status'=>'I']);
    	 	$response['success'] = true;
    	 	$response['message'] = 'Hotel Deactivated Successfully';
    	 	return Response::json($response);
    	 }else{
    	 	Hotel::where('status','!=','D')->where('id',$id)->update(['hotel_status'=>'A']);
    	 	$response['success'] = true;
    	 	$response['message'] = 'Hotel Activated Successfully';
    	 	return Response::json($response);
    	 }
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }		

     }


     public function deleteHotel($id)
     {
     	$response = [];
    	try{
    		Hotel::where('id',$id)->update(['hotel_status'=>'D']);
    		$response['success'] = true;
    	 	$response['message'] = 'Hotel Deleted Successfully';
    		return Response::json($response);
    	}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }	
     }


     public function updateHotel(Request $request)
     {
     	$response = [];
    	try{
    		//valid credential
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'dzongkhag_id' => 'required',
            'gewog_id' => 'required',
            'status' => 'required',
            'stars' => 'required',
            'hotel_type' => 'required',
            'featured' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'latitude' => 'required',
            'deposit_type'=>'required',
            'tax_type'=>'required',
            'id'=>'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $upd = [];
        $upd['name'] = $request->name;
        $upd['description'] = $request->description;
        $upd['dzongkhag_id'] = $request->dzongkhag_id;
        $upd['gewog_id'] = $request->gewog_id;
        $upd['status'] = $request->status;
        $upd['stars'] = $request->stars;
        $upd['hotel_type'] = $request->hotel_type;
        $upd['featured'] = $request->featured;
        $upd['from_date'] = $request->from_date;
        $upd['to_date'] = $request->to_date;
        $upd['longitude'] = $request->longitude;
        $upd['latitude'] = $request->latitude;
        $upd['b2c_discount'] = $request->b2c_discount;
        $upd['b2b_discount'] = $request->b2b_discount;
        $upd['b2e_discount'] = $request->b2e_discount;
        $upd['corporate_discount'] = $request->corporate_discount;
        $upd['service_fees'] = $request->service_fees;
        $upd['deposit_type'] = $request->deposit_type;
        $upd['deposit_value'] = $request->deposit_value;
        $upd['tax_type'] = $request->tax_type;
        $upd['tax_value'] = $request->tax_value;

        Hotel::where('id',$request->id)->update($upd);


        $accessible_destination = $request->accessible_destination;
        if (@$request->accessible_destination) {

        foreach ($accessible_destination as $item) {
            $insDes = [];
            $insDes['hotel_id'] = $request->id;
            $insDes['destination_id'] = $item;
            $checkAvailable = HotelToDestination::where('hotel_id', $request->id)->where('destination_id', $item)->first();
            if ($checkAvailable == null) {
                HotelToDestination::create($insDes);
            }
        }
      }
      if ($accessible_destination) {
        HotelToDestination::where('hotel_id', $request->id)->whereNotIn('destination_id', $accessible_destination)->delete();
      }else{
        HotelToDestination::where('hotel_id', $request->id)->delete();
      }


        $response['success'] = true;	
        $response['message'] = 'Hotel Updated Successfully';
        return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }		
     }



     public function facilitiesHotel($id)
     {  
        $response = [];
        try{
            $facilities = Facilities::select('id','name')->get();
            $hotel_added_facilities = HotelToFacilities::where('hotel_id',$id)->get();
            $response['success'] = true;
            $response['facilities'] = $facilities;
            $response['hotel_added_facilities'] = $hotel_added_facilities;
            return Response::json($response);
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }   
     }

     public function facilitiesUpdateHotel(Request $request)
     {
        $response = [];
        try{
        

        
        if (@$request->facilities) {
          $explode = explode(',', $request->id);
          HotelToFacilities::whereIn('hotel_id',$explode)->delete();

          // return $request;
          
          foreach (@$request->facilities as $key => $value){
            $ins = [];
            $ins['hotel_id'] = $request->id;
            $ins['facility_id'] = $value;
            HotelToFacilities::create($ins);
          }
        }
        
        $response['success'] = true;
        $response['message'] = 'Facilites Updated Successfully';
        return Response::json($response);
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
     }

     public function metaInformationHotel($id)
     {
        $response = [];
        try{
            $metainformation = HotelMetaInformation::where('hotel_id',$id)->first();
            
            $response['success'] = true;
            $response['metainformation'] = $metainformation;
            return Response::json($response);
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }   
     }

     public function metaInformationUpdateHotel(Request $request)
     {
        $response = [];
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'meta_title' => 'required',
            'meta_description' => 'required',
            'meta_keywords' => 'required',
            'id' => 'required',
         ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

          $metainformation = HotelMetaInformation::where('hotel_id',$request->id)->first();  
          // return $metainformation;
          if ($metainformation=='') {
              $ins = [];
              $ins['hotel_id'] = $request->id;
              $ins['meta_title'] = $request->meta_title;
              $ins['meta_description'] = $request->meta_description;
              $ins['meta_keywords'] = $request->meta_keywords;
              HotelMetaInformation::create($ins);
          }else{
              $upd = [];
              $upd['hotel_id'] = $request->id;
              $upd['meta_title'] = $request->meta_title;
              $upd['meta_description'] = $request->meta_description;
              $upd['meta_keywords'] = $request->meta_keywords;
              HotelMetaInformation::where('hotel_id',$request->id)->update($upd);
          }
          $response['success'] = true;
          $response['message'] = 'Data Updated Successfully';
          return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }    

     }



     public function policyHotel($id)
     {
        $response = [];
        try{
            $poilcy = HotelPolicy::where('hotel_id',$id)->first();
            $payments = Payments::select('id','name')->get();
            $selected_payments = HotelPolicyPayment::where('hotel_id',$id)->select('id','payment_id')->with('payment_name')->get();
            $response['success'] = true;
            $response['poilcy'] = $poilcy;
            $response['payments'] = $payments;
            $response['selected_payments'] = $selected_payments;
            return Response::json($response);
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
     }

     public function policyUpdateHotel(Request $request)
     {
        $response = [];
        try{
        $policy = HotelPolicy::where('hotel_id',$request->id)->first();

        //valid credential
        $validator = Validator::make($request->all(), [
            'check_in' => 'required',
            'check_out' => 'required',
            // 'payments' => 'required',
            'terms_policy' => 'required',
            'id' => 'required',
         ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        if ($policy=='') {
              $ins = [];
              $ins['hotel_id'] = $request->id;
              $ins['check_in'] = $request->check_in;
              $ins['check_out'] = $request->check_out;
              $ins['terms_policy'] = $request->terms_policy;
              if ($request->payments) {
                      $explode = explode(',', $request->id);
                      HotelPolicyPayment::whereIn('hotel_id',$explode)->delete();
                        foreach (@$request->payments as $key => $value){
                        $pay = [];
                        $pay['hotel_id'] = $request->id;
                        $pay['payment_id'] = $value;
                        HotelPolicyPayment::create($pay);
                      }
              }  
              
              HotelPolicy::create($ins);
          }else{
              $upd = [];
              $upd['hotel_id'] = $request->id;
              $upd['check_in'] = $request->check_in;
              $upd['check_out'] = $request->check_out;
              $upd['terms_policy'] = $request->terms_policy;
              if ($request->payments) {
                      $explode = explode(',', $request->id);
                        HotelPolicyPayment::whereIn('hotel_id',$explode)->delete();
                        foreach (@$request->payments as $key => $value){
                        $pay = [];
                        $pay['hotel_id'] = $request->id;
                        $pay['payment_id'] = $value;
                        HotelPolicyPayment::create($pay);
                      }
              }
              HotelPolicy::where('hotel_id',$request->id)->update($upd);
          }
          
          $response['success'] = true;
          $response['message'] = 'Data Updated Successfully';
          return Response::json($response);    
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }     
     }




     public function contactHotel($id)
     {
        $response = [];
        try{
            $contact = HotelContact::where('hotel_id',$id)->first();
            $response['success'] = true;
            $response['contact'] = $contact;
            return Response::json($response);
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
     }


     public function contactUpdateHotel(Request $request)
     {
        $response = [];
        try{

        $policy = HotelContact::where('hotel_id',$request->id)->first();

        //valid credential
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'website' => 'required',
            'mobile' => 'required',
            'phone' => 'required',
            'toll_free' => 'required',
            'id' => 'required',
         ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        if ($policy=='') {
              $ins = [];
              $ins['hotel_id'] = $request->id;
              $ins['email'] = $request->email;
              $ins['website'] = $request->website;
              $ins['mobile'] = $request->mobile;
              $ins['phone'] = $request->phone;
              $ins['toll_free'] = $request->toll_free;
              HotelContact::create($ins);
          }else{
              $upd = [];
              $upd['hotel_id'] = $request->id;
              $upd['email'] = $request->email;
              $upd['website'] = $request->website;
              $upd['mobile'] = $request->mobile;
              $upd['phone'] = $request->phone;
              $upd['toll_free'] = $request->toll_free;
              HotelContact::where('hotel_id',$request->id)->update($upd);
          }  

          $response['success'] = true;
          $response['message'] = 'Data Updated Successfully';
          return Response::json($response);

        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }     
     }



     public function imagesAddHotel(Request $request)
     {
        $response = [];
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'images' => 'required',
            'name' => 'required',
            'description' => 'required',
            'id' => 'required',
         ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['name'] = $request->name;
        $ins['description'] = $request->description;
        $ins['alt_text'] = $request->alt_text;
        $ins['hotel_id'] = $request->id;
        if ($request->hasFile('images'))
        {
             $image = $request->images;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/hotel_image",$filename);
             $ins['images'] = $filename;
        }


         // if ($file = $request->file('images')) {
         //        //store file into document folder
         //        $files = $request->file('images')->store('storage/app/public/hotel_image');
         //        $ins['images'] = $files;
      
         //    }

        HotelToImages::create($ins);
        $response['success'] = true;
        $response['message'] = 'Images inserted Successfully';
        return Response::json($response);


        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
     }



     public function imagesListingHotel($id)
     {
        $response = [];
        try{
         $images = HotelToImages::where('hotel_id',$id)->get();
         $response['success'] = true;
         $response['images'] = $images;   
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }    
     }


     public function imagesEditViewgHotel($id)
     {
        $response = [];
        try{
         $data = HotelToImages::where('id',$id)->first();
         $response['success'] = true;
         $response['data'] = $data;   
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
     }


     public function imagesUpdateHotel(Request $request)
     {
        $response = [];
        try{

        //valid credential
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'id' => 'required',
         ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }    

        $img = HotelToImages::where('id',$request->id)->first();
        $upd = [];
        $upd['name'] = $request->name;
        $upd['description'] = $request->description;
        $upd['alt_text'] = $request->alt_text;
        // $upd['hotel_id'] = $request->id;
        if ($request->hasFile('images'))
        {
            @unlink('storage/app/public/hotel_image/'.$img->images);
             $image = $request->images;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/hotel_image",$filename);
             $upd['images'] = $filename;
        }  

        HotelToImages::where('id',$request->id)->update($upd);
        $response['success'] = true;
        $response['message'] = 'Image Updated Successfully';
        return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }    
     }



     public function imagesDeletegHotel($id)
     {
        $response = [];
        try{
        $img = HotelToImages::where('id',$id)->first();  
        @unlink('storage/app/public/hotel_image/'.$img->images);  
        HotelToImages::where('id',$id)->delete();
        $response['success'] = true;
        $response['message'] = 'Image Deleted Successfully';
        return Response::json($response);

        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }     
     }



     public function videosAddHotel(Request $request)
     {
        $response = [];
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'video' => 'required',
            'name' => 'required',
            'description' => 'required',
            'id' => 'required',
         ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['name'] = $request->name;
        $ins['description'] = $request->description;
        $ins['alt_text'] = $request->alt_text;
        $ins['hotel_id'] = $request->id;
        if ($request->hasFile('video'))
        {
             $image = $request->video;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/hotel_video",$filename);
             $ins['video'] = $filename;
        }

        HotelToVideos::create($ins);
        $response['success'] = true;
        $response['message'] = 'Video inserted Successfully';
        return Response::json($response);


        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
     }




     public function videosListingHotel($id)
     {
        $response = [];
        try{
         $videos = HotelToVideos::where('hotel_id',$id)->get();
         $response['success'] = true;
         $response['videos'] = $videos;   
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }    
     }


     public function videosEditViewHotel($id)
     {
        $response = [];
        try{
         $data = HotelToVideos::where('id',$id)->first();
         $response['success'] = true;
         $response['data'] = $data;   
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
     }


     public function videosUpdateHotel(Request $request)
     {
        $response = [];
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'id' => 'required',
         ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }    

        $img = HotelToVideos::where('id',$request->id)->first();
        $upd = [];
        $upd['name'] = $request->name;
        $upd['description'] = $request->description;
        $upd['alt_text'] = $request->alt_text;
        // $upd['hotel_id'] = $request->id;
        if ($request->hasFile('video'))
        {
            @unlink('storage/app/public/hotel_video/'.$img->video);
             $image = $request->video;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/hotel_video",$filename);
             $upd['video'] = $filename;
        }  

        HotelToVideos::where('id',$request->id)->update($upd);
        $response['success'] = true;
        $response['message'] = 'Video Updated Successfully';
        return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }   
     

    }






    public function videosDeletegHotel($id)
    {
        $response = [];
        try{
        $img = HotelToVideos::where('id',$id)->first();  
        @unlink('storage/app/public/hotel_video/'.$img->video);  
        HotelToVideos::where('id',$id)->delete();
        $response['success'] = true;
        $response['message'] = 'Video Deleted Successfully';
        return Response::json($response);

        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
    }


    public function imagesStatusHotel($id)
    {
      $response = [];
      try{
       $tour = HotelToImages::where('status','!=','D')->where('id',$id)->first();
       if (@$tour->status=="A") {
        HotelToImages::where('status','!=','D')->where('id',$id)->update(['status'=>'I']);
        $response['success'] = true;
        $response['message'] = 'Image Deactivated Successfully';
        return Response::json($response);
       }else{
        HotelToImages::where('status','!=','D')->where('id',$id)->update(['status'=>'A']);
        $response['success'] = true;
        $response['message'] = 'Image Activated Successfully';
        return Response::json($response);
       }
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
    }

    












}
