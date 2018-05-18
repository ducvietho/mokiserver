<?php

namespace App\Http\Controllers;

use App\Model\Product;
use App\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getOrderDeatail(Request $request){
        $idProduct = $request->input('product_id');
        if(!empty($idProduct)){
            $product = Product::select('id','image','name','price','customer_id','ships_from')->where('id',$idProduct)->first();
            $buyer = User::find($product->customer_id);
            $product->buyer = $buyer;
            return response([
                'code' => 200,
                'message' => "Success",
                'data' => $product
            ]);
        }
    }
}
