<?php

namespace App\Http\Controllers;
use App\Model\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getCategories(){
        $category = Category::all();
        return response([
            'code' => 200,
            'message' => "Success",
            'data' => $category
        ]);
    }
}
