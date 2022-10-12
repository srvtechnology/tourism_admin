<?php

namespace App\Http\Controllers\Api\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Response;
use App\Models\CmsSubSubCategory;
use App\Models\CmsSubCategory;
use App\Models\CmsCategory;
use App\Models\Cms;
use App\Models\LandingPage;
use Illuminate\Support\Facades\Http;
use Storage;
class CmsController extends Controller
{
    public function getSubSubCategory(Request $request)
    {
        $response = [];
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'sub_category_id'=>'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $data = CmsSubSubCategory::where('sub_category_id',$request->sub_category_id)->where('status','!=','D')->get();
        $response['data'] = $data;
        $response['success'] = true;
        return Response::json($response);

        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }

    public function addView()
    {
        $response = [];
        try{
            


           $response['success'] = true;
           $response['category'] = CmsCategory::where('status','!=','D')->get(); 
           return $response;
        
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
            'category_id'=>'required',
            'article'=>'required',
            'sub_category_id'=>'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['category_id'] = $request->category_id;
        $ins['sub_category_id'] = $request->sub_category_id;
        $ins['sub_sub_category_id'] = $request->sub_sub_category_id;
        $ins['article'] = $request->article;
        
        Cms::create($ins);
        $response['success'] = true;
        $response['message'] = 'Data Inserted Successfully ';
        return $response;

        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
    }

    public function list()
    {
        $response = [];
        try{
           $response['success'] = true;
           $response['data'] = Cms::with('category_name','subcategory_name','sub_subcategory_name')->where('status','!=','D')->get(); 
           return $response;
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
    }

    public function edit($id)
    {
        $response = [];
        try{
           $response['success'] = true;
           $response['data'] = Cms::where('id',$id)->first();
           $response['category'] = CmsCategory::where('status','!=','D')->get();
           $response['sub_category'] = CmsSubCategory::where('status','!=','D')->where('category_id',$response['data']->category_id)->get();
           $response['sub_sub_category'] = CmsSubSubCategory::where('status','!=','D')->where('sub_category_id',$response['data']->sub_category_id)->get();
           return $response;
        
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
            'category_id'=>'required',
            'article'=>'required',
            'sub_category_id'=>'required',
            'id'=>'required'
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['category_id'] = $request->category_id;
        $ins['sub_category_id'] = $request->sub_category_id;
        $ins['article'] = $request->article;
        $ins['sub_sub_category_id   '] = $request->sub_sub_category_id ;
        Cms::where('id',$request->id)->update($ins);
        $response['success'] = true;
        $response['message'] = 'Data Inserted Successfully ';
        return $response;

        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        } 
    }


    public function dataGet()
    {
        $response = [];
        try{
    //         $name = 'Attraction Name : Test Poi Name';
    //         $attraction_details = 'Attraction Details : In publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate the visual form of a document or a typeface without relying on meaningful content. Lorem ipsum may be used as a placeholder before final copy is available.';
    //         $business = 'Business Oparating Hours : 11:30 - 2:30';
    //         $additional = " Dos and Don't : In publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate the visual form of a document or a typeface without relying on meaningful content. Lorem ipsum may be used";
    //         $contact = 'Contact Person : Sayan Ghosh';
    //         $mobile = 'Mobile : 8617260203';

    //         $data = 'Poi Details :-     
                          
    // '.$name.'

    // '.$attraction_details.'

    // '.$business.'

    // '.$additional.'

    // '.$contact.'

    // '.$mobile.'';

            

    //         $request = Http::get('https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl='.$data.'   ');
    //         if ($request->ok()) {
    //         Storage::put('filename.png', $request->body());
    //         }
           $response['success'] = true;
           $response['data'] = LandingPage::where('id',1)->first(); 
           $response['image_link'] = 'http://services.tourism.gov.bt/storage/app/public/landing_page/';
           return $response;
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function dataUpdate(Request $request)
    {
        $response = [];
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'title'=>'required',
            'description_one'=>'required',
            'description_two'=>'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['title'] = $request->title;
        $ins['description_one'] = $request->description_one;
        $ins['description_two'] = $request->description_two;
        if ($request->hasFile('image'))
        {
             $image = $request->image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/landing_page",$filename);
             $ins['image'] = $filename;
        }
        LandingPage::where('id',1)->update($ins);
        $response['success'] = true;
        $response['message'] = 'Data Updated Successfully ';
        return $response;

        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function fetchCategory()
    {
        $response = [];
        try{
           $response['success'] = true;
           $response['data'] = CmsCategory::where('status','!=','D')->get(); 
           return $response;
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function fetchInnerCategory($id)
    {
        $response = [];
        try{
           $response['success'] = true;
           $response['data'] = CmsSubCategory::with('sub_subcategory_name')->where('category_id',$id)->get(); 
           return $response;
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function fetchCms($id)
    {
        $response = [];
        try{
           $response['success'] = true;
           $response['data'] = Cms::with('category_name','subcategory_name','sub_subcategory_name')->where('status','!=','D')->where('sub_sub_category_id',$id)->get(); 
           return $response;
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }

    
}
