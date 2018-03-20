<?php

namespace App\Http\Controllers;
use App\Model\Comment;
use App\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function getCommentsByProduct(Request $request){
        $idProduct = $request->input('product_id');
        $list = Comment::query()->where('product_id',$idProduct)->orderBy('id','desc')->get();
        $lastId = $list[0]->id;
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
        if(!empty($comment)&&!empty($lastId)){
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
        }
        return response([
            'code' => 1002,
            'message' => 'Parameter is no enough',
        ]);
    }
}
