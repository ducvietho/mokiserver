<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function uploadImageProduct(Request $request){
        $this->validate($request, [
            'input_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('input_img')) {
            $image = $request->file('input_img');
            $name = $image->getClientOriginalName();
            $destinationPath = public_path('/upload/product');
            $image->move($destinationPath, $name);
            return response([
                'code'=>200,
                'message'=>'Success',
            ]);
        }
        return response([
            'code'=>1002,
            'message'=>'Failure',
        ]);
    }
    public function uploadImageUser(Request $request){
        $this->validate($request, [
            'input_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('input_img')) {
            $image = $request->file('input_img');
            $name = $image->getClientOriginalName();
            $destinationPath = public_path('/upload/user');
            $image->move($destinationPath, $name);
            return response([
                'code'=>200,
                'message'=>'Success',
            ]);
        }
        return response([
            'code'=>1002,
            'message'=>'Failure',
        ]);
    }
}
