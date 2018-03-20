<?php

namespace App\Http\Controllers;
use App\Model\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function likeProduct(Request $request){
        $idProduct = $request->input('product_id');
        $idUser = $request->input('user_id');
        $like = Like::create([
            'product_id'=>$idProduct,
            'user_id'=>$idUser
        ]);
        if(!empty($like)){
            $numberLike = Like::where('product_id',$idProduct)->count();
            return response([
                'code' => 200,
                'message' => "Success",
                'data' => [
                    'like'=>$numberLike
                ]

            ]);
        }
    }
    public function unlikeProduct(Request $request){
        $idProduct = $request->input('product_id');
        $idUser = $request->input('user_id');
        $like = Like::where('product_id',$idProduct)->where('user_id',$idUser)->first();
        $result = $like->delete();
        if($result){
            $numberLike = Like::where('product_id',$idProduct)->count();
            return response([
                'code' => 200,
                'message' => "Success",
                'data' => [
                    'like'=>$numberLike
                ]

            ]);
        }
    }
}
