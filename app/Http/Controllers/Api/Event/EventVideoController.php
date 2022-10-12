<?php

namespace App\Http\Controllers\Api\Event;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventVideoModel;
use Validator;
use Response;


class EventVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        try{
            $event_videos=EventVideoModel::with('getEventDetails')->orderBy('id','desc')->get();
            $response['success']['event_videos'] = $event_videos;
            return Response::json($response);
           } catch (\Throwable $th) {
            $response['error']['message'] = $th->getMessage();
            return Response::json($response);
           } 
           return response()->json([ 'valid' => auth()->check() ]);
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
                'event_id'        => 'required', 
                'video_name'        => 'required',   
                'video_description'        => 'required',   
                'video_alt_text'        => 'required',   
                'video_file' => 'required',
            ]);

            if ($validator->fails()) {
                $response['error']['validation'] = $validator->errors();
                return Response::json($response);
            }

            $ins_data=new EventVideoModel;
            $ins_data->event_id=$request->event_id;
            $ins_data->video_name=$request->video_name;
            $ins_data->video_description=$request->video_description;
            $ins_data->video_alt_text=$request->video_alt_text;
            
            if ($request->hasFile('video_file'))
            {
                 $header_image = $request->video_file;
                 $filename = time() . '-' . ranvideo_filed(1000, 9999) . '.' . $header_image->getClientOriginalExtension();
                 $header_image->move("storage/app/public/event",$filename);
                 $ins_data->video_file  = $filename;
            }


            $ins_data->save();

            $response['success']['message']="Event Video Inserted";
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
        
        try{
            $single_category=EventVideoModel::with('getEventDetails')->where('id',$id)->first();
            if($single_category){
                $response['success']['single_event_video'] = $single_category;
                return Response::json($response);
            }else{
                $response['error']['message'] = "This id does not exist";
                return Response::json($response);
            }
            
           } catch (\Throwable $th) {
            $response['error']['message'] = $th->getMessage();
            return Response::json($response);
           }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
            $search_cat=EventVideoModel::where('id',$id)->first();
            if($search_cat){
                EventVideoModel::where('id',$id)->delete();
                $response['success']['message'] ="Event Video Deleted";
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

     public function update_2(Request $request, $id)
    {
        
        try{
            $srch_reg=EventVideoModel::where('id',$id)->first();
            $up=array();
            if($srch_reg){
                if($request->event_id){
                    $up['event_id']=$request->event_id;
                }
                if($request->video_name){
                    $up['video_name']=$request->video_name;
                }
                if($request->video_description){
                    $up['video_description']=$request->video_description;
                }
                if($request->video_alt_text){
                    $up['video_alt_text']=$request->video_alt_text;
                }
                if($request->file('video_file')){
                     $header_image = $request->video_file;
                 $filename = time() . '-' . ranvideo_filed(1000, 9999) . '.' . $header_image->getClientOriginalExtension();
                 $header_image->move("storage/app/public/event",$filename);
                 $$up['video_file']  = $filename;
            
                }
               // dd($up);
                    $update=EventVideoModel::where('id',$id)->update($up);
                    $fetch_category=EventVideoModel::where('id',$id)->first();
                        $response['success']['message'] = "Category updated";
                        $response['success']['region'] = $fetch_category;
                        return Response::json($response);
            }else{
                $response['error']['message'] = "This is does not exists for update";
                return Response::json($response);
            }
           
            
           } catch (\Throwable $th) {
            $response['error']['message'] = $th->getMessage();
            return Response::json($response);
           } 
    }
}
