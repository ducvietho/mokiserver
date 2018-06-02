<?php

namespace App\Http\Controllers;

use App\Model\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function getOrderDeatail(Request $request)
    {
        $idProduct = $request->input('product_id');
        if (!empty($idProduct)) {
            $product = DB::table('order_product')->join('product', 'order_product.product_id', '=', 'product.id')
                ->select('product.id', 'product.image', 'product.name', 'product.price', 'order_product.customer_id', 'order_product.address')
                ->where('order_product.product_id', '=', $idProduct)->first();
            // $product = Product::select('id','image','name','price','ships_from')->where('id',$idProduct)->first();
            $buyer = User::find($product->customer_id);
            $product->ships_from = $product->address;
            $product->buyer = $buyer;
            return response([
                'code' => 200,
                'message' => "Success",
                'data' => $product
            ]);
        }
    }
}
