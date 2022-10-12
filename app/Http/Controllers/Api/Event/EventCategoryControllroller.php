<?php

namespace App\Http\Controllers\Api\Event;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventCategoryModel;
use Validator;
use Response;

class EventCategoryControllroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
       
        try{
        $event_categorys=EventCategoryModel::orderBy('id','desc')->get();
        $response['success']['event_categorys'] = $event_categorys;
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
    public function create(Request $request)
    {
       
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
                'category'        => 'required', 
                // 'description'        => 'required',   
            ]);

            if ($validator->fails()) {
                $response['error']['validation'] = $validator->errors();
                return Response::json($response);
            }

            $ins_data=new EventCategoryModel;
            $ins_data->category=$request->category;
            $ins_data->description=$request->description;
            $ins_data->save();

            $response['success']['message']="Category Inserted";
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
            $single_category=EventCategoryModel::where('id',$id)->first();
            if($single_category){
                $response['success']['single_category'] = $single_category;
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


    public function update_2(Request $request,$id)
    {
            
        try{
            $srch_cat=EventCategoryModel::where('id',$id)->first();
            $up=array();
            if($srch_cat){
                if($request->category){
                    $up['category']=$request->category;
                }
                if($request->description){
                    $up['description']=$request->description;
                }
               // dd($up);
                    $update=EventCategoryModel::where('id',$id)->update($up);
                    $fetch_category=EventCategoryModel::where('id',$id)->first();
                        $response['success']['message'] = "Category updated";
                        $response['success']['category'] = $fetch_category;
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
            $search_cat=EventCategoryModel::where('id',$id)->first();
            if($search_cat){
                EventCategoryModel::where('id',$id)->delete();
                $response['success']['message'] ="Category Deleted";
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
