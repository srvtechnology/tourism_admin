<?php

namespace App\Http\Controllers\Api\Review;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Response;
use App\Models\Review;
class ReviewController extends Controller
{
    public function postReview(Request $request)
    {
        $response = [];
        try{
        //valid credential
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'type' => 'required',
            'rating' => 'required',
            'review' => 'required',
            'name' => 'required',
            'recomended' => 'required',

            'from_country' => 'required',
            'email' => 'required',
       ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

       $review = new Review;
       if ($request->type=="A") {
          $review->activity_id = $request->id;
       }
       if ($request->type=="P") {
          $review->poi_id = $request->id;
       }
       if ($request->type=="B") {
          $review->blog_id = $request->id;
       }

       if ($request->type=="E") {
          $review->event_id = $request->id;
       }

        $review->rating = $request->rating;
        $review->review = $request->review;
        $review->name = $request->name;

        $review->recomended = $request->recomended;
        $review->from_country = $request->from_country;
        $review->email = $request->email;

        $review->save();
        $response['success'] = true;
        $response['message'] = 'Review Done Successfully';
        return $response;

        
        

        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }
}
