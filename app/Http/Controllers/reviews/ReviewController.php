<?php

namespace App\Http\Controllers\reviews;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\GeneralTrait;
use Validator;
use App\Models\Review;
use App\Models\Product;

class ReviewController extends Controller
{
    use GeneralTrait;

    public function index()
    {
        try{
            $msg='all reviews';
            $data = Review::all();
            return $this->successResponse($data,$msg);
        }
        catch (\Exception $ex){
            return $this->errorResponse($ex->getMessage(),500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
            'product_id' => 'required',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->errors(),422);
        }

        try {

            $product = Product::find( $request->product_id);
            $review = Review::create($request->all());
            $review->product()->associate($product)->save();
           $data=$review;
           $msg='review is created successfully';
            return $this->successResponse($data,$msg,201);
        }
        catch (\Exception $ex)
        {
            return $this->errorResponse($ex->getMessage(),500);
        }
    }

    public function show($id)
    {
        try{
            $data=Review::with('product')->find($id);
            if(!$data)
                return $this->errorResponse('No review with such id',404);
            $msg='Got you the review';
            return $this->successResponse($data,$msg);
        }
        catch (\Exception $ex){
            return $this->errorResponse($ex->getMessage(),500);
        }    
    }

    public function destroy($id)
    {
        try{
            $data=Review::find($id);
            if(!$data)
                return $this->errorResponse('No review with such id',404);

            $data->delete();
            $msg='The review is deleted successfully';
            return $this->successResponse($data,$msg);
        }
        catch (\Exception $ex){
            return $this->errorResponse($ex->getMessage(),500);
        }
    }

    }
