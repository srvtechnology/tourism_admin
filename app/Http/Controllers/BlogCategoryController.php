<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogCategoryModel;
use Validator;
use Response;


class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $blog_categorys=BlogCategoryModel::orderBy('id','desc')->get();
            $response['success']['blog_categorys'] = $blog_categorys;
            $response['image_link'] = 'http://services.tourism.gov.bt/storage/app/public/blog_thumbnail/';
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
                'blog_name'        => 'required',   
                'blog_details'        => 'required',   
                'blog_thumbnail' => 'required',
            ]);

            if ($validator->fails()) {
                $response['error']['validation'] = $validator->errors();
                return Response::json($response);
            }

            $ins_data=new BlogCategoryModel;
            $ins_data->blog_name=$request->blog_name;
            $ins_data->blog_details=$request->blog_details;
            
            if ($request->hasFile('blog_thumbnail'))
            {
             $image = $request->blog_thumbnail;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/blog_thumbnail",$filename);
             $ins_data->blog_thumbnail = $filename;
            }

            $ins_data->save();
            $response['success']['message']="Blog Category Inserted";
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
            $single_category=BlogCategoryModel::where('id',$id)->first();
            if($single_category){
                $response['success']['single_blog_category'] = $single_category;
                $response['image_link'] = 'http://services.tourism.gov.bt/storage/app/public/blog_thumbnail/';
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
    public function update_2(Request $request, $id)
    {
          try{
            $srch_reg=BlogCategoryModel::where('id',$id)->first();
            $up=array();
            if($srch_reg){
                if($request->blog_name){
                    $up['blog_name']=$request->blog_name;
                }
                if($request->blog_details){
                    $up['blog_details']=$request->blog_details;
                }
                if ($request->hasFile('blog_thumbnail'))
                {
                 $image = $request->blog_thumbnail;
                 $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
                 $image->move("storage/app/public/blog_thumbnail",$filename);
                 $up['blog_thumbnail'] = $filename;
                }
               // dd($up);
                    $update=BlogCategoryModel::where('id',$id)->update($up);
                    $fetch_category=BlogCategoryModel::where('id',$id)->first();
                        $response['success']['message'] = "Blog Category updated";
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $search_cat=BlogCategoryModel::where('id',$id)->first();
            if($search_cat){
                BlogCategoryModel::where('id',$id)->delete();
                $response['success']['message'] ="Blog Category Deleted";
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
