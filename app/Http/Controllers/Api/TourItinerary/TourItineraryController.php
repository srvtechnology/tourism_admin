<?php

namespace App\Http\Controllers\Api\TourItinerary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use App\Models\User;
use App\Models\TourItinerary;
use App\Models\TourCategory;
use App\Models\TourToMeta;
use App\Models\TourToContact;
use Response;
use App\Models\TourToPolicy;
use App\Models\TourToInclusion;
use App\Models\Inclusion;
use App\Models\Exclusion;
use App\Models\TourToExclusion;
use App\Models\TourToImage;
use App\Models\TourToVideo;
use App\Models\TourToHotel;
use App\Models\FeaturedFlight;
use App\Models\TourToFlight;
use App\Models\TourToDetails;
use App\Models\Hotel;
use App\Models\Destination;
use App\Models\Region;
use App\Models\RelatedTour;
use App\Models\Payments;
use App\Models\TourToPolicyPayment;
class TourItineraryController extends Controller
{
    public function addView()
    {
    	$response = [];
        try{
         $response['category'] = TourCategory::select('id','name')->get();
         $response['related_tour'] = TourItinerary::where('status','!=','DE')->select('id','name')->get();
         $response['region'] = Region::select('id','name')->get();
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
            'region_id'=>'required',
            'category_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'adults_number' => 'required',


            'adults_price' => 'required',
            'children_number' => 'required',
            'children_price' => 'required',
            'infants_number' => 'required',


            'infants_price' => 'required',
            'lat' => 'required',
            'lon' => 'required',
            'status' => 'required',


            'stars' => 'required',
            'tour_days' => 'required',
            'tour_nights' => 'required',
            'tour_hours' => 'required',

            'featured' => 'required',
            'deposit_type' => 'required',
            'deposit_amount' => 'required',
            'tax_type' => 'required',

            'tax_amount' => 'required',
            'price' => 'required',
            
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['region_id'] = $request->region_id;
        $ins['category_id'] = $request->category_id;
        $ins['name'] = $request->name;
        $ins['description'] = $request->description;
        $ins['adults_number'] = $request->adults_number;


        $ins['adults_price'] = $request->adults_price;
        $ins['children_number'] = $request->children_number;
        $ins['children_price'] = $request->children_price;
        $ins['infants_number'] = $request->infants_number;

        $ins['infants_price'] = $request->infants_price;
        $ins['lat'] = $request->lat;
        $ins['lon'] = $request->lon;
        $ins['status'] = $request->status;

        $ins['stars'] = $request->stars;
        $ins['tour_days'] = $request->tour_days;
        $ins['tour_nights'] = $request->tour_nights;
        $ins['tour_hours'] = $request->tour_hours;

        $ins['featured'] = $request->featured;
        $ins['deposit_type'] = $request->deposit_type;
        $ins['deposit_amount'] = $request->deposit_amount;
        $ins['tax_type'] = $request->tax_type;

        $ins['tax_amount'] = $request->tax_amount;
        $ins['price'] = $request->price;
        
        



        $tour = TourItinerary::create($ins);

        if ($request->related_tour) {
        $related_tour = $request->related_tour;
        foreach ($related_tour as $value) {
            $insdes = [];
            $insdes['tour_id'] = $tour->id;
            $insdes['related_tour_id'] = $value;
            RelatedTour::create($insdes);
          }
        }

       


        $response['success'] = true;	
        $response['message'] = 'Tour Itinerary Created Successfully';
        return Response::json($response);

    	
    	}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function listing(Request $request)
    {
    	$response = [];
        try{
         $response['region'] = Region::select('id','name')->get(); 
         $response['category'] = TourCategory::select('id','name')->get();
         $data = TourItinerary::with('category_name')->with('region_name')->where('status','!=','DE');
         if (@$request->region) {
            $data = $data->where('region_id',$request->region);
         }
         if (@$request->category) {
           $data = $data->where('category_id',$request->category);
         }

         $response['data'] = $data->get();
         $response['success'] = true;
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
         $check = TourItinerary::where('id',$id)->first();
         if (@$check->status=="E") {
            TourItinerary::where('id',$id)->update(['status'=>'D']);
            $response['success'] = true;
            $response['message'] = 'Status Disabled Successfully';
            return Response::json($response);
         }else{
            TourItinerary::where('id',$id)->update(['status'=>'E']);
            $response['success'] = true;
            $response['message'] = 'Status Enabled Successfully';
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
            TourItinerary::where('id',$id)->update(['status'=>'DE']);
            $response['success'] = true;
            $response['message'] = 'Tour Itinerary Deleted Successfully';
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
         $response['category'] = TourCategory::select('id','name')->get();
         $response['region'] = Region::get();
         $response['data'] = TourItinerary::where('id',$id)->first();
         $response['related_tour'] = TourItinerary::where('status','!=','DE')->where('id','!=',$response['data']->id)->get();
         $response['selected_related_tour'] = RelatedTour::with('tour_name')->where('tour_id',$id)->get();
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
           'region_id'=>'required',
            'category_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'adults_number' => 'required',


            'adults_price' => 'required',
            'children_number' => 'required',
            'children_price' => 'required',
            'infants_number' => 'required',


            'infants_price' => 'required',
            'lat' => 'required',
            'lon' => 'required',
            'status' => 'required',


            'stars' => 'required',
            'tour_days' => 'required',
            'tour_nights' => 'required',
            'tour_hours' => 'required',

            'featured' => 'required',
            'deposit_type' => 'required',
            'deposit_amount' => 'required',
            'tax_type' => 'required',

            'tax_amount' => 'required',
            'price' => 'required',
            
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $upd = [];
        $upd['region_id'] = $request->region_id;
        $upd['category_id'] = $request->category_id;
        $upd['name'] = $request->name;
        $upd['description'] = $request->description;
        $upd['adults_number'] = $request->adults_number;


        $upd['adults_price'] = $request->adults_price;
        $upd['children_number'] = $request->children_number;
        $upd['children_price'] = $request->children_price;
        $upd['infants_number'] = $request->infants_number;

        $upd['infants_price'] = $request->infants_price;
        $upd['lat'] = $request->lat;
        $upd['lon'] = $request->lon;
        $upd['status'] = $request->status;

        $upd['stars'] = $request->stars;
        $upd['tour_days'] = $request->tour_days;
        $upd['tour_nights'] = $request->tour_nights;
        $upd['tour_hours'] = $request->tour_hours;

        $upd['featured'] = $request->featured;
        $upd['deposit_type'] = $request->deposit_type;
        $upd['deposit_amount'] = $request->deposit_amount;
        $upd['tax_type'] = $request->tax_type;

        $upd['tax_amount'] = $request->tax_amount;
        $upd['price'] = $request->price;
        
        



        TourItinerary::where('id',$request->id)->update($upd);

        if ($request->related_tour) {
        $explode = explode(',', $request->id);  
        RelatedTour::whereIn('tour_id',$explode)->delete();  
        $related_tour = $request->related_tour;
        foreach ($related_tour as $value) {
            $insdes = [];
            $insdes['tour_id'] = $request->id;
            $insdes['related_tour_id'] = $value;
            RelatedTour::create($insdes);
          }
        }

       


        $response['success'] = true;    
        $response['message'] = 'Tour Itinerary Updated Successfully';
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
         $response['data'] = TourItinerary::with('category_name')->where('status','!=','DE')->where('id',$id)->first();
         $response['success'] = true;
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function metaInfoView($id)
    {
        $response = [];
        try{
         $response['data'] = TourToMeta::where('tour_id',$id)->first();
         $response['success'] = true;
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }

    public function metaInfoUpdate(Request $request)
    {
        $response = [];
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'meta_title' => 'required',
            'meta_description' => 'required',
            'meta_keyword' => 'required',
            'id' => 'required',
         ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

          $metainformation = TourToMeta::where('tour_id',$request->id)->first();  
          // return $metainformation;
          if ($metainformation=='') {
              $ins = [];
              $ins['tour_id'] = $request->id;
              $ins['meta_title'] = $request->meta_title;
              $ins['meta_description'] = $request->meta_description;
              $ins['meta_keyword'] = $request->meta_keyword;
              TourToMeta::create($ins);
          }else{
              $upd = [];
              $upd['tour_id'] = $request->id;
              $upd['meta_title'] = $request->meta_title;
              $upd['meta_description'] = $request->meta_description;
              $upd['meta_keyword'] = $request->meta_keyword;
              TourToMeta::where('tour_id',$request->id)->update($upd);
          }
          $response['success'] = true;
          $response['message'] = 'Data Updated Successfully';
          return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
    }



    public function contactView($id)
    {
        $response = [];
        try{
         $response['data'] = TourToContact::where('tour_id',$id)->first();
         $response['success'] = true;
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function contactUpdate(Request $request)
    {
        $response = [];
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            // 'tour_oparator  ' => 'required',
            'email' => 'required',
            'website' => 'required',
            'phone' => 'required',
            'mobile' => 'required',
            'id' => 'required',
         ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

          $contactInformation = TourToContact::where('tour_id',$request->id)->first();  
          // return $metainformation;
          if ($contactInformation=='') {
              $ins = [];
              $ins['tour_id'] = $request->id;
              $ins['tour_oparator'] = $request->tour_oparator;
              $ins['email'] = $request->email;
              $ins['website'] = $request->website;
              $ins['phone'] = $request->phone;
              $ins['mobile'] = $request->mobile;
              TourToContact::create($ins);
          }else{
              $upd = [];
              $upd['tour_id'] = $request->id;
              $upd['tour_oparator'] = $request->tour_oparator;
              $upd['email'] = $request->email;
              $upd['website'] = $request->website;
              $upd['phone'] = $request->phone;
              $upd['mobile'] = $request->mobile;
              TourToContact::where('tour_id',$request->id)->update($upd);
          }
          $response['success'] = true;
          $response['message'] = 'Data Updated Successfully';
          return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
    }

    public function policyView($id)
    {
        $response = [];
        try{
            $data = TourToPolicy::where('tour_id',$id)->first();
            $response['success'] = true;
            $response['data'] = $data;
            $response['payments'] = Payments::select('id','name')->get();
            $response['selected_payments'] = TourToPolicyPayment::where('tour_id',$id)->select('id','payment_id')->with('payment_name')->get();
            return Response::json($response);
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }

    public function policyUpdate(Request $request)
    {
      $response = [];
        try{
        $policy = TourToPolicy::where('tour_id',$request->id)->first();

        //valid credential
        $validator = Validator::make($request->all(), [
            'policy_terms'=>'required',
            'id' => 'required',
         ]);  

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        if ($policy=='') {
              $ins = [];
              $ins['tour_id'] = $request->id;
              $ins['policy_terms'] = $request->policy_terms;
              if ($request->payments) {
                      $explode = explode(',', $request->id);
                      TourToPolicyPayment::whereIn('tour_id',$explode)->delete();
                        foreach (@$request->payments as $key => $value){
                        $pay = [];
                        $pay['tour_id'] = $request->id;
                        $pay['payment_id'] = $value;
                        TourToPolicyPayment::create($pay);
                      }
              } 
              TourToPolicy::create($ins);
          }else{
              $upd = [];
              $upd['tour_id'] = $request->id;
              $upd['policy_terms'] = $request->policy_terms;
              if ($request->payments) {
                      $explode = explode(',', $request->id);
                      TourToPolicyPayment::whereIn('tour_id',$explode)->delete();
                        foreach (@$request->payments as $key => $value){
                        $pay = [];
                        $pay['tour_id'] = $request->id;
                        $pay['payment_id'] = $value;
                        TourToPolicyPayment::create($pay);
                      }
              }
              TourToPolicy::where('tour_id',$request->id)->update($upd);
          }
          
          $response['success'] = true;
          $response['message'] = 'Data Updated Successfully';
          return Response::json($response);    
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }    
    }



    public function inclusionView($id)
    {
        $response = [];
        try{
            $inclusions = Inclusion::select('id','name')->get();
            $tour_added_inclusions = TourToInclusion::where('tour_id',$id)->get();
            $response['success'] = true;
            $response['inclusions'] = $inclusions;
            $response['tour_added_inclusions'] = $tour_added_inclusions;
            return Response::json($response);
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
    }


    public function inclusionUpdate(Request $request)
    {
        $response = [];
        try{
        $explode = explode(',', $request->id);
        TourToInclusion::whereIn('tour_id',$explode)->delete();
        
        if (@$request->inclusions) {
            foreach (@$request->inclusions as $key => $value){
            $ins = [];
            $ins['tour_id'] = $request->id;
            $ins['inclusion_id'] = $value;
            TourToInclusion::create($ins);
          }
        }
        
        $response['success'] = true;
        $response['message'] = 'Inclusions Updated Successfully';
        return Response::json($response);
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
    }



    public function exclusionView($id)
    {
      $response = [];
        try{
            $exclusion = Exclusion::select('id','name')->get();
            $tour_added_exclusion = TourToExclusion::where('tour_id',$id)->get();
            $response['success'] = true;
            $response['exclusion'] = $exclusion;
            $response['tour_added_exclusion'] = $tour_added_exclusion;
            return Response::json($response);
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
    }


    public function exclusionUpdate(Request $request)
    {
        $response = [];
        try{
        $explode = explode(',', $request->id);
        TourToExclusion::whereIn('tour_id',$explode)->delete();
        
        if (@$request->exclusions) {
            foreach (@$request->exclusions as $key => $value){
            $ins = [];
            $ins['tour_id'] = $request->id;
            $ins['exclusion_id'] = $value;
            TourToExclusion::create($ins);
          }
        }
        
        $response['success'] = true;
        $response['message'] = 'Exclusion Updated Successfully';
        return Response::json($response);
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
    }


    public function addImage(Request $request)
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
        $ins['tour_id'] = $request->id;
        if ($request->hasFile('images'))
        {
             $image = $request->images;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/tour_image",$filename);
             $ins['images'] = $filename;
        }

        TourToImage::create($ins);
        $response['success'] = true;
        $response['message'] = 'Images inserted Successfully';
        return Response::json($response);


        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 

    }


    public function ListingImage($id)
    {
       $response = [];
        try{
         $images = TourToImage::where('tour_id',$id)->where('status','!=','D')->get();
         $response['success'] = true;
         $response['images'] = $images;   
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function editImage($id)
    {
      $response = [];
        try{
         $data = TourToImage::where('id',$id)->first();
         $response['success'] = true;
         $response['data'] = $data;   
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function updateImage(Request $request)
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

        $img = TourToImage::where('id',$request->id)->first();
        $upd = [];
        $upd['name'] = $request->name;
        $upd['description'] = $request->description;
        $upd['alt_text'] = $request->alt_text;
        // $upd['hotel_id'] = $request->id;
        if ($request->hasFile('images'))
        {
             @unlink('storage/app/public/tour_image/'.$img->images);
             $image = $request->images;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/tour_image",$filename);
             $upd['images'] = $filename;
        }  

        TourToImage::where('id',$request->id)->update($upd);
        $response['success'] = true;
        $response['message'] = 'Image Updated Successfully';
        return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }    
    }


    public function statusImage($id)
    {
      $response = [];
      try{
       $tour = TourToImage::where('status','!=','D')->where('id',$id)->first();
       if (@$tour->status=="A") {
        TourToImage::where('status','!=','D')->where('id',$id)->update(['status'=>'I']);
        $response['success'] = true;
        $response['message'] = 'Image Deactivated Successfully';
        return Response::json($response);
       }else{
        TourToImage::where('status','!=','D')->where('id',$id)->update(['status'=>'A']);
        $response['success'] = true;
        $response['message'] = 'Image Activated Successfully';
        return Response::json($response);
       }
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
    }


    public function videosStatusHotel($id)
    {
      $response = [];
      try{
       $tour = TourToVideo::where('status','!=','D')->where('id',$id)->first();
       if (@$tour->status=="A") {
        TourToVideo::where('status','!=','D')->where('id',$id)->update(['status'=>'I']);
        $response['success'] = true;
        $response['message'] = 'Image Deactivated Successfully';
        return Response::json($response);
       }else{
        TourToVideo::where('status','!=','D')->where('id',$id)->update(['status'=>'A']);
        $response['success'] = true;
        $response['message'] = 'Image Activated Successfully';
        return Response::json($response);
       }
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
    }



    public function deleteImage($id)
    {
      $response = [];
      try{
        TourToImage::where('id',$id)->update(['status'=>'D']);
        $response['success'] = true;
        $response['message'] = 'Image Deleted Successfully';
        return Response::json($response);
      }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function addVideo(Request $request)
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
        $ins['tour_id'] = $request->id;
        if ($request->hasFile('video'))
        {
             $image = $request->video;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/tour_video",$filename);
             $ins['video'] = $filename;
        }

        TourToVideo::create($ins);
        $response['success'] = true;
        $response['message'] = 'Video inserted Successfully';
        return Response::json($response);


        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
    }


    public function listingVideo($id)
    {
      $response = [];
        try{
         $videos = TourToVideo::where('tour_id',$id)->where('status','!=','D')->get();
         $response['success'] = true;
         $response['videos'] = $videos;   
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }  
    }


    public function statusVideo($id)
    {
      $response = [];
      try{
       $tour = TourToVideo::where('status','!=','D')->where('id',$id)->first();
       if (@$tour->status=="A") {
        TourToVideo::where('status','!=','D')->where('id',$id)->update(['status'=>'I']);
        $response['success'] = true;
        $response['message'] = 'Video Deactivated Successfully';
        return Response::json($response);
       }else{
        TourToVideo::where('status','!=','D')->where('id',$id)->update(['status'=>'A']);
        $response['success'] = true;
        $response['message'] = 'Video Activated Successfully';
        return Response::json($response);
       }
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
    }



    public function deleteVideo($id)
    {
      $response = [];
      try{
        TourToVideo::where('id',$id)->update(['status'=>'D']);
        $response['success'] = true;
        $response['message'] = 'Video Deleted Successfully';
        return Response::json($response);
      }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function editVideo($id)
    {
      $response = [];
        try{
         $data = TourToVideo::where('id',$id)->first();
         $response['success'] = true;
         $response['data'] = $data;   
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function updateVideo(Request $request)
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

        $img = TourToVideo::where('id',$request->id)->first();
        $upd = [];
        $upd['name'] = $request->name;
        $upd['description'] = $request->description;
        $upd['alt_text'] = $request->alt_text;
        // $upd['hotel_id'] = $request->id;
        if ($request->hasFile('video'))
        {
            @unlink('storage/app/public/tour_video/'.$img->video);
             $image = $request->video;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/tour_video",$filename);
             $upd['video'] = $filename;
        }  

        TourToVideo::where('id',$request->id)->update($upd);
        $response['success'] = true;
        $response['message'] = 'Video Updated Successfully';
        return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }   
    }


    public function hotelListing($id)
    {
      $response = [];
      try{

        $hotels = TourToHotel::where('tour_id',$id)->with('hotel_name')->get();
        $response['success'] = true;
        $response['data'] = $hotels;
        
        return Response::json($response);
      }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
      }
    }

    public function hotelSuggesion(Request $request)
    {
      $response = [];
      try{
        if ($request->name!="") {
        $hotels = Hotel::where('name','LIKE','%'.$request->name.'%')->select('id','name')->get();
       }else{
        $hotels =null;
       }
        $response['success'] = true;
        $response['data'] = $hotels;
        return Response::json($response);
      }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
      }
    }


    public function hotelAdd(Request $request)
    {
      $response = [];
      try{
        if (@$request->hotel) {
        $explode = explode(',', $request->id);  
        TourToHotel::whereIn('tour_id',$explode)->delete();
        foreach ($request->hotel as $key => $value) {
          $hotel = new TourToHotel;
          $hotel->hotel_name = $value;
          $hotel->tour_id = $request->id;
          $hotel->save();
        }
       }
        $response['success'] = true;
        $response['message'] = 'Hotel Added Successfully';
        return Response::json($response);
      }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
      }
    }


    public function flightListing($id)
    {
      $response = [];
      try{

        $flight = FeaturedFlight::get();
        $response['success'] = true;
        $response['data'] = $flight;
        $response['selected_flight'] =TourToFlight::where('tour_id',$id)->get();
        return Response::json($response);
      }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
      }
    }


    public function flightUpdate(Request $request)
    {
      $response = [];
      try{
        if ($request->flights) {
         $explode = explode(',', $request->id); 
         TourToFlight::whereIn('tour_id',$explode)->delete();
        foreach ($request->flights as $key => $value) {
          
          
          $flight = new TourToFlight;
          $flight->flight_id = $value;
          $flight->tour_id = $request->id;
          $flight->save();
        }
      }
        $response['success'] = true;
        $response['message'] = 'Flight Updated Successfully';
        return Response::json($response);
      }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
      }
    }


    public function tourDetails($id)
    {
      $response = [];
      try{

        $details = TourToDetails::where('tour_id',$id)->with('destination_name')->get();
        $response['success'] = true;
        $response['data'] = $details;
        return Response::json($response);
      }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
      }
    }

    public function tourDetailsUpdate(Request $request)
    { 
      $response = [];
      try{
      	$explode = explode(',', $request->id);
        TourToDetails::whereIn('tour_id',$explode)->delete();
        
       foreach ($request->hotel as $key => $value) {
        	TourToDetails::create([
              'tour_id' =>$request->id,
              'destination' =>$value['destination'],
              'day' => $value['day'],
              'description' => $value['description'],
          ]);
        }

        $response['success'] = true;
        $response['message'] = 'Details Updated Successfully';
        return Response::json($response);
      }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
      }

    }


    public function tourAllDestination()
    {
    	$response = [];
        try{
         $response['data'] = Destination::get();
         $response['success'] = true;
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }






}
