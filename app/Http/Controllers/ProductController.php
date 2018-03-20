<?php

namespace App\Http\Controllers;

use App\Model\Comment;
use App\Model\Like;
use App\Model\Product;
use App\Model\Category;
use App\User;
use Illuminate\Pagination\Paginator;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProductsByCatagory(Request $request)
    {

        $idCata = $request->input("id_category");
        $page = $request->input("page");
        $idUser = $request->input('idUser');

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });
        if ($page == 1) {
            $products = Product::query()->select('id', 'name', 'seller_id', 'image', 'price')->where('category_id', $idCata)->orderBy('id', 'desc')->paginate(20);
            $list = $products->items();
            foreach ($list as $product) {
                $likeNumber = Like::where('product_id', $product->id)->count();
                $commentNumber = Comment::where('product_id', $product->id)->count();
                $product->comments = $commentNumber;
                $product->like = $likeNumber;
                if (Like::where('product_id', $product->id)->where('user_id', $idUser)->first()) {
                    $product->is_liked = 1;
                } else {
                    $product->is_liked = 0;
                }
                $seller = User::where('id', $product->seller_id)->get()->first();

                $product->seller = [
                    'id' => $seller->id,
                    'name' => $seller->name,
                    'avatar' => $seller->avatar
                ];

                $lastID = $list[0]->id;
            }
            return response([
                'code' => 200,
                'message' => "Success",
                'data' => [
                    'lastId' => $lastID,
                    'products' => $list
                ]
            ]);
        } else {
            $lastID = $request->input('last_id');
            $products = Product::select('id', 'name', 'seller_id', 'image', 'price')->where('category_id', $idCata)->paginate(20);
            $list = $products->items();
            foreach ($list as $product) {
                $likeNumber = Like::where('product_id', $product->id)->count();
                $product->like = $likeNumber;
                $commentNumber = Comment::where('product_id', $product->id)->count();
                $product->comments = $commentNumber;
                if (Like::where('product_id', $product->id)->where('user_id', $idUser)->first()) {
                    $product->is_liked = 1;
                } else {
                    $product->is_liked = 0;
                }
                $seller = User::where('id', $product->seller_id)->get()->first();

                $product->seller = [
                    'id' => $seller->id,
                    'name' => $seller->name,
                    'avatar' => $seller->avatar
                ];

            }
            $new_item_number = Product::query()->where('id', '>', $lastID)->count();

            return response([

                'code' => 200,
                'message' => "Success",
                'data' => [
                    'new_item' => $new_item_number,
                    'products' => $list
                ]
            ]);
        }

    }

    public function getProductDetail(Request $request)
    {
        $idProduct = $request->input('id_product');
        $idUser = $request->input('id_user');
        $product = Product::select('id', 'name', 'seller_id', 'image', 'price', 'category_id', 'described', 'ships_from', 'dimension', 'weight', 'status', 'is_sold', 'created_at')->where('id', $idProduct)->first();

        $likeNumber = Like::where('product_id', $product->id)->count();
        $product->like = $likeNumber;
        $commentNumber = Comment::where('product_id', $product->id)->count();
        $product->comments = $commentNumber;
        if (Like::where('product_id', $product->id)->where('user_id', $idUser)->first()) {
            $product->is_liked = 1;
        } else {
            $product->is_liked = 0;
        }
        $seller = User::where('id', $product->seller_id)->get()->first();
        $numberProductByUser = Product::where('seller_id', $idUser)->count();

        $category = Category::select('name')->where('id', $product->category_id)->first();
        $product->category_name = $category->name;
        $product->seller = [
            'id' => $seller->id,
            'name' => $seller->name,
            'avatar' => $seller->avatar,
            'rate' => $seller->rate,
            'numberProduct' => $numberProductByUser
        ];

        return response([
            'code' => 200,
            'message' => "Success",
            'data' => $product
        ]);
    }

    public function getProductsByUser(Request $request)
    {
        $idUser = $request->input('user_id');
        $idSeller = $request->input('seller_id');
        if(!empty($idSeller)&&!empty($idUser)){
            $products = Product::select('id', 'name', 'image', 'price')->where('seller_id', $idSeller)->get();
            foreach ($products as $product) {
                $likeNumber = Like::where('product_id', $product->id)->count();
                $product->like = $likeNumber;
                $commentNumber = Comment::where('product_id', $product->id)->count();
                $product->comments = $commentNumber;
                if (Like::where('product_id', $product->id)->where('user_id', $idUser)->first()) {
                    $product->is_liked = 1;
                } else {
                    $product->is_liked = 0;
                }
                $seller = User::where('id', $idUser)->get()->first();

                $product->seller = [
                    'id' => $seller->id,
                    'name' => $seller->name,
                    'avatar' => $seller->avatar
                ];

            }
            return response([
                'code' => 200,
                'message' => "Success",
                'data' => [
                    'products' => $products
                ]
            ]);
        }
        return response([
            'code' => 1002,
            'message' => 'Parameter is no enough',
        ]);

    }

    public function getProductsFavoriteByUser(Request $request)
    {
        $idUser = $request->input('user_id');
        $products = Like::query()->select('product_id')->where('user_id', $idUser)->orderBy('id','desc')->get();
        foreach ($products as $product) {
            $pro = Product::select('id','name', 'image', 'price')->where('id', $product->product_id)->first();
            $product->id = $product->product_id;
            $product->name = $pro->name;
            $product->image = $pro->image;
            $product->price = $pro->price;
        }
        return response([
            'code' => 200,
            'message' => "Success",
            'data' => [
                'products' => $products
            ]
        ]);
    }
}
