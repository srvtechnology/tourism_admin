<?php

namespace App\Http\Controllers\Api\Event;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventImageModel;
use Validator;
use Response;


class EventImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $event_categorys=EventImageModel::with('getEventDetails')->orderBy('id','desc')->get();
            $response['success']['event_images'] = $event_categorys;
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
                'image_name'        => 'required',   
                'image_description'        => 'required',   
                'image_alt_text'        => 'required',      
                'image_file' => 'required',
            ]);

            if ($validator->fails()) {
                $response['error']['validation'] = $validator->errors();
                return Response::json($response);
            }

            $ins_data=new EventImageModel;
            $ins_data->event_id=$request->event_id;
            $ins_data->image_name=$request->image_name;
            $ins_data->image_description=$request->image_description;
            $ins_data->image_alt_text=$request->image_alt_text;
            // if ($file = $request->file('image_file')) {
            //     //store file into document folder
            //     $files = $request->file('image_file')->store('public/documents');
            //     $ins_data->image_file = $files;
      
            // }

            if ($request->hasFile('image_file'))
            {
                 $header_image = $request->image_file;
                 $filename = time() . '-' . rand(1000, 9999) . '.' . $header_image->getClientOriginalExtension();
                 $header_image->move("storage/app/public/event",$filename);
                 $ins_data->image_file  = $filename;
            }
            $ins_data->save();

            $response['success']['message']="Event Image Inserted";
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
            $single_category=EventImageModel::with('getEventDetails')->where('id',$id)->first();
            if($single_category){
                $response['success']['single_event_image'] = $single_category;
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
            $search_cat=EventImageModel::where('id',$id)->first();
            if($search_cat){
                EventImageModel::where('id',$id)->delete();
                $response['success']['message'] ="Event Image Deleted";
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
            $srch_reg=EventImageModel::where('id',$id)->first();
            $up=array();
            if($srch_reg){
                if($request->event_id){
                    $up['event_id']=$request->event_id;
                }
                if($request->image_name){
                    $up['image_name']=$request->image_name;
                }
                if($request->image_description){
                    $up['image_description']=$request->image_description;
                }
                if($request->image_alt_text){
                    $up['image_alt_text']=$request->image_alt_text;
                }
                if($request->file('image_file'))
                {
                
                 $header_image = $request->image_file;
                 $filename = time() . '-' . rand(1000, 9999) . '.' . $header_image->getClientOriginalExtension();
                 $header_image->move("storage/app/public/event",$filename);
                 $up['image_file']  = $filename;
                }
               // dd($up);
                    $update=EventImageModel::where('id',$id)->update($up);
                    $fetch_category=EventImageModel::where('id',$id)->first();
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
