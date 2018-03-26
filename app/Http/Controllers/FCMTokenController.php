<?php

namespace App\Http\Controllers;

use App\Model\FCMToken;
use Illuminate\Http\Request;

class FCMTokenController extends Controller
{
    public function createToken(Request $request){
        $idUser = $request->input('user_id');
        $token = $request->input('token');
        if(!empty($idUser)&&!empty($token)){
            $fcm = FCMToken::where('user_id',$idUser)->first();
            if(!empty($fcm)){
                $update = FCMToken::where('user_id',$idUser)->update([
                    'token'=>$token
                ]);
                if($update){
                    return response([
                        'code' => 200,
                        'message' => "Update Success",
                    ]);
                }
                return response([
                    'code' => 400,
                    'message' => 'Failure',
                ]);
            }else{
                $create = FCMToken::create([
                    'user_id'=>$idUser,
                    'token'=>$token
                ]);
                if($create){
                    return response([
                        'code' => 200,
                        'message' => "Create Success",
                    ]);
                }
                return response([
                    'code' => 400,
                    'message' => 'Failure',
                ]);
            }
        }
        return response([
            'code' => 1002,
            'message' => 'Parameter is no enough',
        ]);
    }
}
