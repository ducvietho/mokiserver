<?php

namespace App\Http\Controllers;
use App\Model\FCMToken;
use App\Model\Like;
use App\Model\Product;
use App\Model\Notification;
use App\User;
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
        $product = Product::find($idProduct);
        $user = User::find($idUser);
        $token = FCMToken::find($idUser);
        $key = $token->token;
        if($idUser != $product->seller_id){
            $msg = array(
                'body' => $user->name.' đã thích về '.$product->name.' của bạn',
                'title' => 'Moki',
                'icon' => 'myicon',
                'sound' => 1
            );
            app('App\Http\Controllers\NotificationController')->pushNotification($key, $msg);
            Notification::create([
                'product_id'=>$idProduct,
                'title'=>$user->name.' đã like về '.$product->name.' của bạn',
                'type'=>1,
                'from_id'=>$idUser,
                'to_id'=>$product->seller_id
            ]);
        }
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
