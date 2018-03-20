<?php

namespace App\Http\Controllers;

use App\Model\OrderAddress;
use Illuminate\Http\Request;

class AddressOrderController extends Controller
{
    public function getAddressSeller(Request $request){
        $idSeller = $request->input('user_id');
        if(!empty($idSeller)){
            $address = OrderAddress::where('user_id',$idSeller)->get();
            return response([
                'code' => 200,
                'message' => "Success",
                'data' => $address
            ]);
        }
        return response([
            'code' => 1002,
            'message' => 'Parameter is no enough',
        ]);
    }
    public function setAddressSeller(Request $request){
        $idSeller = $request->input('user_id');
        $address = $request->input('address');
        if(!empty($idSeller)&&!empty($address)){
            $add = OrderAddress::create([
                'user_id'=>$idSeller,
                'address'=>$address
            ]);
            if(!empty($add)){
                return response([
                    'code'=>200,
                    'message'=>'Success',
                ]);

            }
        }
        return response([
            'code' => 1002,
            'message' => 'Parameter is no enough',
        ]);
    }
}
