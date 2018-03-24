<?php

namespace App\Http\Controllers;
use App\Model\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function getNews(){
       $news = News::all();
        return response([
            'code'=>200,
            'message'=>'Success',
            'data'=>$news
        ]);
    }
}
