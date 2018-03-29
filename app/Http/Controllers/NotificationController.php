<?php

namespace App\Http\Controllers;

use App\Model\Notification;
use App\Model\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;


class NotificationController extends Controller
{
    public function pushNotification($key, $data)
    {
        $msg = array
        (
            'body' => 'Comment',
            'title' => 'Moki',
            'icon' => 'myicon',/*Default Icon*/
            'sound' => 1/*Default sound*/
        );
        $fields = array();
        $fields['notification'] = $data;
        if (is_array($key)) {
            $fields['registration_ids'] = $key;
        } else {
            $fields['to'] = $key;
        }

        $headers = array
        (
            'Authorization: key=' . Config::get('constants.SERVER_KEY'),
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);


    }

    public function getMessageNotification(Request $request)
    {
        $idUser = $request->input('user_id');
        if (!empty($idUser)) {
            $notifications = Notification::where('to_id', $idUser)->where('type', 1)->get();
            foreach ($notifications as $notification){
                $product = Product::find($notification->product_id);
                $notification->image = $product->image;
            }
            return response([
                'code' => 200,
                'message' => "Success",
                'data' => $notifications
            ]);

        }
        return response([
            'code' => 1002,
            'message' => 'Parameter is no enough',
        ]);
    }

    public function getNotification(Request $request)
    {
        $idUser = $request->input('user_id');
        if (!empty($idUser)) {
            $notifications = Notification::where('to_id', $idUser)->where('type', 0)->get();
            foreach ($notifications as $notification){
                $product = Product::find($notification->product_id);
                $notification->image = $product->image;
            }
            return response([
                'code' => 200,
                'message' => "Success",
                'data' => $notifications
            ]);

        }
        return response([
            'code' => 1002,
            'message' => 'Parameter is no enough',
        ]);
    }
}
