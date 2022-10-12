<?php

namespace App\Http\Controllers\Api\Event;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventModel;
use Validator;
use Response;
use App\Models\EventVideoModel;
use App\Models\EventImageModel;
use App\Jobs\EventCreated;
use App\Models\Review;


class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_1(Request $request)
    {
        
        // try{
            $r=$request['regionName'];
            $g=$request['gewogName'];
            $dz=$request['DzongName'];
            $ca=$request['CategoryName'];

            if($request->all()){
                $posts = EventModel::whereHas('getRegionDetails',function($query) use($r){
                    $query->where('name', @$r);
                })->orWhereMonth('created_at', $request->month)->orWhereYear('created_at',$request->year)
    
    
                ->orWhereHas('getGewogDetails',function($query) use($g) {
                    $query->where('name',@$g);
                })->orWhereMonth('created_at', $request->month)->orWhereYear('created_at',$request->year)
    
    
                ->orWhereHas('getDzongkhagDetails',function($query) use($dz){
                    $query->where('name', @$dz);
                })->orWhereMonth('created_at', $request->month)->orWhereYear('created_at',$request->year)
    
    
    
                ->orWhereHas('getEventCategoryDetails',function($query) use($ca){
                    $query->where('category', @$ca);
                })->orWhereMonth('created_at', $request->month)->orWhereYear('created_at',$request->year)
    
    
                ->with('getRegionDetails','getGewogDetails','getDzongkhagDetails','getDungkhagDetails','getVillageDetails','getEventCategoryDetails')
                ->get();
              
            }else{
            $posts=EventModel::with('getRegionDetails','getGewogDetails','getDzongkhagDetails','getDungkhagDetails','getVillageDetails','getEventCategoryDetails')->orderBy('id','desc')->get();

            }

           
        
           
            // dd($posts);

      
            // $events=EventModel::with('getRegionDetails','getGewogDetails','getDzongkhagDetails','getDungkhagDetails','getVillageDetails','getEventCategoryDetails')->orderBy('id','desc')->get();
            $response['success']['events'] = $posts;
            return Response::json($response);
        //    } catch (\Throwable $th) {
        //     $response['error']['message'] = $th->getMessage();
        //     return Response::json($response);
        //    } 
           return response()->json([ 'valid' => auth()->check() ]);
    }

    public function months(){
        $data = [];
        $events=EventModel::get();
        foreach ($events as $value) {
            array_push($data,$value->created_at->format('M'));
        }
        $unique_values = array_values(array_unique($data));
        return Response::json($unique_values);
    }
    
    public function years(){
        $data = [];
        $events=EventModel::get();
        foreach ($events as $value) {
            array_push($data,$value->created_at->format('Y'));
        }
        $unique_values = array_values(array_unique($data));
        return Response::json($unique_values);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'region_id'        => 'required', 
                'dzongkhag_id'        => 'required',   
                // 'dungkhag_id'        => 'required',   
                // 'gewog_id'        => 'required',   
                // 'village_id'        => 'required',   
                'event_category_id'        => 'required',   
                'event_name'        => 'required',
                'event_details'        => 'required',
                'inbound_option'        => 'required',
                'local_travel'        => 'required',


                'event_of_attraction'        => 'required',
                'start_date'        => 'required',
                'end_date'        => 'required',

                'latitude'        => 'required',
                'longitude'        => 'required',
            ]);

            if ($validator->fails()) {
                $response['error']['validation'] = $validator->errors();
                return Response::json($response);
            }

            $ins_data=new EventModel;
            $ins_data->region_id=$request->region_id;
            $ins_data->dzongkhag_id=$request->dzongkhag_id;
            $ins_data->dungkhag_id=$request->dungkhag_id;
            $ins_data->gewog_id=$request->gewog_id;
            $ins_data->village_id=$request->village_id;
            $ins_data->event_category_id=$request->event_category_id;
            $ins_data->event_name = $request->event_name;
            $ins_data->event_details = $request->event_details;
            $ins_data->salient_activities = $request->salient_activities;
            $ins_data->inbound_option = $request->inbound_option;
            $ins_data->local_travel = $request->local_travel;
            $ins_data->cultural_advice = $request->cultural_advice;
            $ins_data->event_of_attraction = $request->event_of_attraction;
            $ins_data->weather_seasonal_condition = $request->weather_seasonal_condition;
            $ins_data->available_amenities = $request->available_amenities;
            $ins_data->disable_friendly_services = $request->disable_friendly_services;
            $ins_data->accomodation_information = $request->accomodation_information;
            $ins_data->start_date = $request->start_date;
            $ins_data->end_date = $request->end_date;
            $ins_data->latitude = $request->latitude;
            $ins_data->longitude = $request->longitude;
            if ($request->hasFile('header_image'))
            {
                 $header_image = $request->header_image;
                 $filename = time() . '-' . rand(1000, 9999) . '.' . $header_image->getClientOriginalExtension();
                 $header_image->move("storage/app/public/event",$filename);
                 $ins_data->header_image = $filename;
            }

            if ($request->hasFile('teaser_image'))
            {
                 $teaser_image = $request->teaser_image;
                 $filename = time() . '-' . rand(1000, 9999) . '.' . $teaser_image->getClientOriginalExtension();
                 $teaser_image->move("storage/app/public/event",$filename);
                 $ins_data->teaser_image = $filename;
            }
            $ins_data->save();
            // EventCreated::dispatch($ins_data->toArray());


            $response['success']['message']="Event Inserted";
            return Response::json($response);
        } catch (\Throwable $th) {
            $response['error']['message'] = $th->getMessage();
            return Response::json($response);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $response = [];
        try{
            
            $single_event=EventModel::with('getRegionDetails','getGewogDetails','getDzongkhagDetails','getDungkhagDetails','getVillageDetails','getEventCategoryDetails')->where('id',$id)->first();
            
                $response['success'] = true;
                $response['single_event'] = $single_event;
                $response['image_link'] = 'http://services.tourism.gov.bt/storage/app/public/event/';
                $response['images'] = EventImageModel::where('event_id',$id)->get();
                $response['videos'] = EventVideoModel::where('event_id',$id)->get();


                // review
                 $response['reviews'] = Review::where('activity_id',$id)->get();
                 $response['total_review_count'] = Review::where('activity_id',$id)->count();
                 $response['avg_review'] = Review::where('activity_id',$id)->avg('rating');
                 $response['one_star'] = Review::where('activity_id',$id)->where('rating',1)->count();
                 $response['two_star'] = Review::where('activity_id',$id)->where('rating',2)->count();
                 $response['three_star'] = Review::where('activity_id',$id)->where('rating',3)->count();
                 $response['four_star'] = Review::where('activity_id',$id)->where('rating',4)->count();
                 $response['five_star'] = Review::where('activity_id',$id)->where('rating',5)->count();
                return Response::json($response);
            
            
           } catch (\Throwable $th) {
            $response['error']['message'] = $th->getMessage();
            return Response::json($response);
           }
    }


    public function index()
    {  
       
        try{
        $events=EventModel::with('getRegionDetails','getGewogDetails','getDzongkhagDetails','getDungkhagDetails','getVillageDetails','getEventCategoryDetails')->get();
        $response['success']['events'] = $events;
        $response['image_link'] = 'http://services.tourism.gov.bt/storage/app/public/event/';
        return Response::json($response);
       } catch (\Throwable $th) {
        $response['error']['message'] = $th->getMessage();
        return Response::json($response);
       } 
       return response()->json([ 'valid' => auth()->check() ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $response = [];
            $single_event=EventModel::with('getRegionDetails','getGewogDetails','getDzongkhagDetails','getDungkhagDetails','getVillageDetails','getEventCategoryDetails')->where('id',$id)->first();
            
                $response['success'] = true;
                // $response['single_event'] = $single_event;
                $response['image_link'] = 'http://services.tourism.gov.bt/storage/app/public/event/';
                $response['images'] = EventImageModel::where('event_id',$id)->get();
                $response['videos'] = EventVideoModel::where('event_id',$id)->get();
                return Response::json($response);
            
            
           } catch (\Throwable $th) {
            $response['error']['message'] = $th->getMessage();
            return Response::json($response);
           } 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_2(Request $request, $id)
    {
        
        try{
            $srch_cat=EventModel::where('id',$id)->first();
            $up=array();
            if($srch_cat){
                if($request->region_id){
                    $up['region_id']=$request->region_id;
                }
                if($request->dzongkhag_id){
                    $up['dzongkhag_id']=$request->dzongkhag_id;
                }
                if($request->dungkhag_id){
                    $up['dungkhag_id']=$request->dungkhag_id;
                }
                if($request->gewog_id){
                    $up['gewog_id']=$request->gewog_id;
                }
                if($request->village_id){
                    $up['village_id']=$request->village_id;
                }
                if($request->event_category_id){
                    $up['event_category_id']=$request->event_category_id;
                }
                if($request->event_name){
                    $up['event_name']=$request->event_name;
                }
                if($request->event_details){
                    $up['event_details']=$request->event_details;
                }
                if($request->salient_activities){
                    $up['salient_activities']=$request->salient_activities;
                }
                if($request->inbound_option){
                    $up['inbound_option']=$request->inbound_option;
                }
                if($request->local_travel){
                    $up['local_travel']=$request->local_travel;
                }
                if($request->cultural_advice){
                    $up['cultural_advice']=$request->cultural_advice;
                }
                if($request->event_of_attraction){
                    $up['event_of_attraction']=$request->event_of_attraction;
                }
                if($request->weather_seasonal_condition){
                    $up['weather_seasonal_condition']=$request->weather_seasonal_condition;
                }
                if($request->available_amenities){
                    $up['available_amenities']=$request->available_amenities;
                }
                if($request->disable_friendly_services){
                    $up['disable_friendly_services']=$request->disable_friendly_services;
                }
                if($request->accomodation_information){
                    $up['accomodation_information']=$request->accomodation_information;
                }
                if($request->start_date){
                    $up['start_date']=$request->start_date;
                }
                if($request->end_date){
                    $up['end_date']=$request->end_date;
                }
                if($request->latitude){
                    $up['latitude']=$request->latitude;
                }
                if($request->longitude){
                    $up['longitude']=$request->longitude;
                }

                if ($request->hasFile('header_image'))
                {
                 $header_image = $request->header_image;
                 $filename = time() . '-' . rand(1000, 9999) . '.' . $header_image->getClientOriginalExtension();
                 $header_image->move("storage/app/public/event",$filename);
                 $up['header_image'] = $filename;
                }

                if ($request->hasFile('teaser_image'))
                {
                 $teaser_image = $request->teaser_image;
                 $filename = time() . '-' . rand(1000, 9999) . '.' . $teaser_image->getClientOriginalExtension();
                 $teaser_image->move("storage/app/public/event",$filename);
                 $up['teaser_image'] = $filename;
                }
                    $update=EventModel::where('id',$id)->update($up);
                    $fetch_category=EventModel::where('id',$id)->first();
                        $response['success']['message'] = "Category updated";
                        $response['success']['events'] = $fetch_category;
                        return Response::json($response);
            }else{
                $response['error']['message'] = "This id does not exists for update";
                return Response::json($response);
            }
           
            
           } catch (\Throwable $th) {
            $response['error']['message'] = $th->getMessage();
            return Response::json($response);
           } 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $search_cat=EventModel::where('id',$id)->first();
            if($search_cat){
                EventModel::where('id',$id)->delete();
                $response['success']['message'] ="Event Deleted";
                return Response::json($response);
            }else{
                $response['error']['message'] = "This id does not exist for delete";
                return Response::json($response);
            }
            
           } catch (\Throwable $th) {
            $response['error']['message'] = $th->getMessage();
            return Response::json($response);
           } 
    }
}
