<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\User;
use Illuminate\Support\Facades\Input;
use App\Model\Product;
class UserController extends Controller
{
    public function get(Request $request)
    {
        $input = Input::all();
        if (isset($input['user_id'])) {
            $user = User::find($input['user_id']);
            if (isset($user)) {
                $numberProductByUser = Product::where('seller_id', $input['user_id'])->count();
                $user->numberProduct = $numberProductByUser;
                return response([
                    'status_code' => 1000,
                    'message' => 'OK',
                    'data' => $user,
                ]);
            }
        }
        return response([
            'status_code' => 1002,
            'message' => 'Parameter is not enough',

        ]);
    }

    public function store(Request $request)
    {
        $user = User::create([
            'name' => 'Danh Chuong',
            'phone' => '0988973418',
            'token' => str_random(60),
            'password' => bcrypt('123'),
            'dob' => '',
            'avatars' => 'http://is4.mzstatic.com/image/thumb/Purple71/v4/42/3c/20/423c200e-f2f3-7ef3-0d30-56d7dfc14684/source/175x175bb.jpg',
        ]);
        if (!empty($user)) {
            return response([
                'code' => 1000,
                'message' => 'Register success',
            ]);
        }
    }

    public function login(Request $request)
    {
        $input = $request->only(['phonenumber', 'password']);

        try {
            $credentials = [
                'phone' => $input['phonenumber'],
                'password' => $input['password'],
            ];
            $token = null;
            try {
                if (!$token = JWTAuth::attempt($credentials)) {
                    return response([
                        'code' => '9998',
                        'message' => 'Token is invalid.',
                    ]);
                }
            } catch (JWTAuthException $e) {
                return response()->json(['failed_to_create_token'], 500);
            }
            $user = JWTAuth::toUser($token);
            $user->token = $token;
            $user->save();
            return response([
                'code' => '1000',
                'message' => 'Login is success',
                'data' => $user
            ]);
        } catch (Exception $ex) {
            return response([
                'code' => 500,
                'message' => 'Something went wrong',
            ]);
        }

    }
}
