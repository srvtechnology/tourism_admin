<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogPostModel;
use App\Models\Review;
use Validator;
use Response;


class BlogPostController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         try{
            $event_videos=BlogPostModel::with('getPostDetails')->orderBy('id','desc')->get();
            $response['success']['event_videos'] = $event_videos;
            return Response::json($response);
           } catch (\Throwable $th) {
            $response['error']['message'] = $th->getMessage();
            return Response::json($response);
           } 
           return response()->json([ 'valid' => auth()->check() ]);
    
    }


    public function showBlogs()
    {
        $response = [];
        try{
         $data = BlogPostModel::with('getPostDetails')->orderBy('id','desc')->get();
         $response['success'] = true;
         $response['data'] = $data;
         $response['image_link'] = 'http://services.tourism.gov.bt/storage/app/public/blog_thumbnail/';

        


         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }



    public function showBlogsDetails($id)
    {
        $response = [];
        try{
         $data = BlogPostModel::with('getPostDetails')->where('id',$id)->first();
         $response['success'] = true;
         $response['data'] = $data;
         $response['image_link'] = 'http://services.tourism.gov.bt/storage/app/public/blog_thumbnail/';

          // review
         $response['reviews'] = Review::where('blog_id',$id)->get();
         $response['total_review_count'] = Review::where('blog_id',$id)->count();
         $response['avg_review'] = Review::where('blog_id',$id)->avg('rating');
         $response['one_star'] = Review::where('blog_id',$id)->where('rating',1)->count();
         $response['two_star'] = Review::where('blog_id',$id)->where('rating',2)->count();
         $response['three_star'] = Review::where('blog_id',$id)->where('rating',3)->count();
         $response['four_star'] = Review::where('blog_id',$id)->where('rating',4)->count();
         $response['five_star'] = Review::where('blog_id',$id)->where('rating',5)->count();


         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
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
                'blog_category_id'        => 'required', 
                'post_title'        => 'required',   
                'permalink'        => 'required',   
                'post_details'        => 'required',   
                'thumbnail' => 'required',
                'seo'=>'required',
                'post_desc'=>'required',
            ]);

            if ($validator->fails()) {
                $response['error']['validation'] = $validator->errors();
                return Response::json($response);
            }

            $ins_data=new BlogPostModel;
            $ins_data->blog_category_id=$request->blog_category_id;
            $ins_data->post_title=$request->post_title;
            $ins_data->permalink=$request->permalink;
            $ins_data->post_details=$request->post_details;
            $ins_data->seo=$request->seo;
            $ins_data->post_desc=$request->post_desc;
            $ins_data->related_post=$request->related_post;
            

            if ($request->hasFile('thumbnail'))
            {
             $image = $request->thumbnail;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/blog_thumbnail",$filename);
             $ins_data->thumbnail = $filename;
            }
            $ins_data->save();

            $response['success']['message']="Blog Post Inserted";
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
            $single_category=BlogPostModel::with('getPostDetails')->where('id',$id)->first();
            if($single_category){
                $response['success']['single_post_detail'] = $single_category;
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
            $srch_reg=BlogPostModel::where('id',$id)->first();
            $up=array();
            if($srch_reg){
                if($request->blog_category_id){
                    $up['blog_category_id']=$request->blog_category_id;
                }
                if($request->post_title){
                    $up['post_title']=$request->post_title;
                }
                if($request->permalink){
                    $up['permalink']=$request->permalink;
                }
                if($request->post_details){
                    $up['post_details']=$request->post_details;
                }
                if($request->seo){
                    $up['seo']=$request->seo;
                }
                 if($request->post_desc){
                    $up['post_desc']=$request->post_desc;
                }
                 if($request->status){
                    $up['status']=$request->status;
                }
                 if($request->related_post){
                    $up['related_post']=$request->related_post;
                }
                if ($request->hasFile('thumbnail'))
                {
                 $image = $request->thumbnail;
                 $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
                 $image->move("storage/app/public/blog_thumbnail",$filename);
                 $$up['thumbnail'] = $filename;
                }
               // dd($up);
                    $update=BlogPostModel::where('id',$id)->update($up);
                    $fetch_category=BlogPostModel::where('id',$id)->first();
                        $response['success']['message'] = "Blog Post Updated";
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
            $search_cat=BlogPostModel::where('id',$id)->first();
            if($search_cat){
                BlogPostModel::where('id',$id)->delete();
                $response['success']['message'] ="Blog Post Deleted";
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
