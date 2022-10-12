<?php

namespace App\Http\Controllers\Api\Dzongkhag;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dzongkhag;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Response;
use App\Models\Region;
class DzongkhagController extends Controller
{
    public function addView()
    {
        $response = [];
        try{
         $data = Region::get();
         $response['success'] = true;
         $response['regions'] = $data;
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
            'region_id' => 'required',
            'name' => 'required',
            'introduction' => 'required',
            'geography' => 'required',

            'flora_and_fauna' => 'required',
            'history' => 'required',
            'festival' => 'required',
            // 'dzongkhag_header_image ' => 'required',

            'dzongkhag_teaser_image' => 'required',
            'dos_and_dont' => 'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ins = [];
        $ins['region_id'] = $request->region_id;
        $ins['name'] = $request->name;
        $ins['introduction'] = $request->introduction;
        $ins['geography'] = $request->geography;

        $ins['flora_and_fauna'] = $request->flora_and_fauna;
        $ins['history'] = $request->history;
        $ins['festival'] = $request->festival;
        $ins['dos_and_dont'] = $request->dos_and_dont;
        
        if ($request->hasFile('dzongkhag_header_image'))
        {
             $image = $request->dzongkhag_header_image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/dzongkhag_header_image",$filename);
             $ins['dzongkhag_header_image'] = $filename;
        }

        if ($request->hasFile('dzongkhag_teaser_image'))
        {
             $image = $request->dzongkhag_teaser_image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/dzongkhag_teaser_image",$filename);
             $ins['dzongkhag_teaser_image'] = $filename;
        }

        Dzongkhag::create($ins);
        $response['success'] = true;
        $response['message'] = 'Dzongkhag Added Successfully';
        return Response::json($response);
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }

    public function listing()
    {
        $response = [];
        try{
         $data = Dzongkhag::with('region_name')->get();
         $response['success'] = true;
         $response['data'] = $data;
         $response['dzongkhag_header_image'] = 'http://services.tourism.gov.bt/storage/app/public/dzongkhag_header_image/'; 
         $response['dzongkhag_teaser_image'] = 'http://services.tourism.gov.bt/storage/app/public/dzongkhag_teaser_image/';   
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
         $data = Dzongkhag::where('id',$id)->first();
         $response['success'] = true;
         $response['data'] = $data;
         $response['regions'] = Region::get();
         $response['dzongkhag_header_image'] = 'http://services.tourism.gov.bt/storage/app/public/dzongkhag_header_image/'; 
         $response['dzongkhag_teaser_image'] = 'http://services.tourism.gov.bt/storage/app/public/dzongkhag_teaser_image/';    
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
            'region_id' => 'required',
            'name' => 'required',
            'introduction' => 'required',
            'geography' => 'required',

            'flora_and_fauna' => 'required',
            'history' => 'required',
            'festival' => 'required',
            'id'=>'required',
            'dos_and_dont' => 'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $img = Dzongkhag::where('id',$request->id)->first();

        $ins = [];
        $ins['region_id'] = $request->region_id;
        $ins['name'] = $request->name;
        $ins['introduction'] = $request->introduction;
        $ins['geography'] = $request->geography;

        $ins['flora_and_fauna'] = $request->flora_and_fauna;
        $ins['history'] = $request->history;
        $ins['festival'] = $request->festival;
        $ins['dos_and_dont'] = $request->dos_and_dont;
        
        if ($request->hasFile('dzongkhag_header_image'))
        {
             @unlink('storage/app/public/dzongkhag_header_image/'.$img->dzongkhag_header_image);
             $image = $request->dzongkhag_header_image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/dzongkhag_header_image",$filename);
             $ins['dzongkhag_header_image'] = $filename;
        }

        if ($request->hasFile('dzongkhag_teaser_image'))
        {
             @unlink('storage/app/public/dzongkhag_teaser_image/'.$img->dzongkhag_teaser_image);
             $image = $request->dzongkhag_teaser_image;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/dzongkhag_teaser_image",$filename);
             $ins['dzongkhag_teaser_image'] = $filename;
        }

        Dzongkhag::where('id',$request->id)->update($ins);
        $response['success'] = true;
        $response['message'] = 'Dzongkhag Added Successfully';
        return Response::json($response);
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


    public function delete($id)
    {
        $response = [];
        try{
         $check = Dzongkhag::where('id',$id)->delete();
         $response['success'] = true;
         $response['message'] = 'Dzongkhag deleted Successfully';   
         return Response::json($response);
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }
}
