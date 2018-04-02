<?php

namespace App\Http\Controllers;
use App\Model\Comment;
use App\Model\FCMToken;
use App\Model\Notification;
use App\Model\Product;
use App\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function getCommentsByProduct(Request $request){
        $idProduct = $request->input('product_id');
        $lastId=0;
        $list = Comment::query()->where('product_id',$idProduct)->orderBy('id','desc')->get();
        if($list->count()>0){
            $lastId = $list[0]->id;
        }

        foreach ($list as $comment){
            $seller = User::where('id', $comment->poster_id)->get()->first();
            $comment->poster = [
                'id' => $seller->id,
                'name' => $seller->name,
                'avatar' => $seller->avatar,
                'rate' => $seller->rate
            ];

        }
        return response([
            'code' => 200,
            'message' => "Success",
            'data' => [
                'lastId'=>$lastId,
                'comments'=>$list
                ]
        ]);
    }
    public function postCommentProduct(Request $request){
        $idProduct = $request->input('product_id');
        $idUser = $request->input("user_id");
        $content = $request->input('content');
        $lastId = $request->input('last_id');
        $comment = Comment::create([
            'product_id'=>$idProduct,
            'poster_id'=>$idUser,
            'content'=>$content
        ]);
        $user = User::find($idUser);
        $product = Product::find($idProduct);
        $seller = User::find($product->seller_id);

        $idComments = Comment::query()->select('poster_id')->where('product_id',$idProduct)
            ->where('poster_id','!=',$idUser)->distinct()->get();

        foreach ($idComments as $comment){
            $token = FCMToken::query()->where('user_id',$comment->poster_id)->first();
            $notifi = Notification::create([
                'product_id'=>$idProduct,
                'title'=>$user->name.' đã bình luận về '.$product->name.' của '.$seller->name,
                'type'=>0,
                'from_id'=>$idUser,
                'to_id'=>$comment->poster_id
            ]);
            $msg = array(
                'body' => $user->name.' đã bình luận về '.$product->name.' của '.$seller->name,
                'title' => 'Moki',
                'icon' => 'myicon',
                'sound' => 1,

            );
            app('App\Http\Controllers\NotificationController')->pushNotification($token->token, $msg,json_encode($notifi));
        }
        if(!empty($comment)){
            if($lastId>0){
                $list = Comment::query()->where('product_id',$idProduct)->orderBy('id','desc')->get();
                $lastIdNew = $list[0]->id;
                $comments = Comment::query()->where('product_id',$idProduct)->where('id','>',$lastId)->orderBy('id','desc')->get();
                foreach ($comments as $item){
                    $seller = User::where('id', $item->poster_id)->get()->first();
                    $item->poster = [
                        'id' => $seller->id,
                        'name' => $seller->name,
                        'avatar' => $seller->avatar,
                        'rate' => $seller->rate
                    ];

                }
                return response([
                    'code' => 200,
                    'message' => "Success",
                    'data' => [
                        'lastId'=>$lastIdNew,
                        'comments'=>$comments
                    ]

                ]);
            }else{
                $list = Comment::query()->where('product_id',$idProduct)->orderBy('id','desc')->get();
                $lastIdNew = $list[0]->id;
                $comments = Comment::query()->where('product_id',$idProduct)->orderBy('id','desc')->get();
                foreach ($comments as $item){
                    $seller = User::where('id', $item->poster_id)->get()->first();
                    $item->poster = [
                        'id' => $seller->id,
                        'name' => $seller->name,
                        'avatar' => $seller->avatar,
                        'rate' => $seller->rate
                    ];

                }
                return response([
                    'code' => 200,
                    'message' => "Success",
                    'data' => [
                        'lastId'=>$lastIdNew,
                        'comments'=>$comments
                    ]

                ]);
            }

        }
        return response([
            'code' => 1002,
            'message' => 'Parameter is no enough',
        ]);
    }
}
