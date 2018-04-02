<?php

namespace App\Http\Controllers;

use App\Model\Notification;
use App\Model\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;


class NotificationController extends Controller
{
    public function pushNotification($key, $msg, $data)
    {
//        $msg = array
//        (
//            'body' => 'Comment',
//            'title' => 'Moki',
//            'icon' => 'myicon',/*Default Icon*/
//            'sound' => 1/*Default sound*/
//        );
        $fields = array();
        $fields['notification'] = $msg;
        $fields['data'] = [
           'notifi'=> $data
        ];
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
            $notifications = Notification::query()->where('to_id', $idUser)->where('type', '=', 2)
                ->orderBy('id', 'desc')->get();
            foreach ($notifications as $notification) {
                $product = Product::find($notification->product_id);
                if ($product->seller_id == $idUser) {
                    $notification->is_seller = 1;
                } else {
                    $notification->is_seller = 0;
                }
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
            $notifications = Notification::query()->where('to_id', $idUser)->where('type', '!=', 2)
                ->orderBy('id', 'desc')->get();
            foreach ($notifications as $notification) {
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

    public function countMessageNotificationUnread(Request $request)
    {
        $idUser = $request->input('user_id');
        if (!empty($idUser)) {
            $count = Notification::query()->where('to_id', $idUser)->where('read', '=', 0)
                ->where('type', '=', 2)
                ->count();

            return response([
                'code' => 200,
                'message' => "Success",
                'number' => $count
            ]);

        }
        return response([
            'code' => 1002,
            'message' => 'Parameter is no enough',
        ]);
    }

    public function countNotificationUnread(Request $request)
    {
        $idUser = $request->input('user_id');
        if (!empty($idUser)) {
            $count = Notification::query()->where('to_id', $idUser)->where('type', '!=', 2)
                ->where('read', '=', 0)
                ->count();
            return response([
                'code' => 200,
                'message' => "Success",
                'number' => $count
            ]);

        }
        return response([
            'code' => 1002,
            'message' => 'Parameter is no enough',
        ]);
    }

    public function setReadNotification(Request $request)
    {
        $idUser = $request->input('user_id');
        if (!empty($idUser)) {
            $read = Notification::query()->where('to_id', $idUser)->where('type', '=', 1)
                ->orWhere('type', '=', 0)->where('read', '=', 0)->update([
                    'read' => 1
                ]);
            if ($read) {
                return response([
                    'code' => 200,
                    'message' => "Success",

                ]);
            }


        }
        return response([
            'code' => 1002,
            'message' => 'Parameter is no enough',
        ]);
    }

    public function setReadMessageNotification(Request $request)
    {
        $idMessage = $request->input('notification_id');
        if (!empty($idMessage)) {
            $read = Notification::query()->where('id', $idMessage)
                ->update([
                    'read' => 1
                ]);

            return response([
                'code' => 200,
                'message' => "Success",

            ]);

        }
        return response([
            'code' => 1002,
            'message' => 'Parameter is no enough',
        ]);
    }
}
