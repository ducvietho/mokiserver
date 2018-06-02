<?php

namespace App\Http\Controllers;

use App\Model\Conversation;
use App\Model\FCMToken;
use App\Model\Message;
use App\Model\Product;
use App\User;
use Illuminate\Http\Request;
use App\Model\Notification;

class ConversationController extends Controller
{
    public function getConversation(Request $request){
        $idUser1 = $request->input('user_id1');
        $idUser2 = $request->input('user_id2');
        $idProduct = $request->input('product_id');
        $conversationId = Conversation::select('id')->where('product_id',$idProduct)->where('user_id1',$idUser1)->where('user_id2',$idUser2)->first();
        if(empty($conversationId)){
            $conversation = Conversation::create([
                'product_id'=>$idProduct,
                'user_id1'=>$idUser1,
                'user_id2'=>$idUser2
            ]);
            if(!empty($conversation)){
                $id = Conversation::select('id')->where('product_id',$idProduct)->where('user_id1',$idUser1)->where('user_id2',$idUser2)->first();

                return response([
                    'code'=>200,
                    'message'=>'Create Conversation Success',
                    'data'=>$id

                ]);
            }
        }else{

            return response([
                'data'=>$conversationId
            ]);
        }
        return response([
            'code' => 1002,
            'message' => 'Parameter is no enough',
        ]);

    }
    public function getMessagesConversation(Request $request){
        $idUser1 = $request->input('user_id1');
        $idUser2 = $request->input('user_id2');
        $idProduct = $request->input('product_id');
        if(!empty($idUser2)&&!empty($idUser1)&&!empty($idProduct)){
            $conversationId = Conversation::select('id')->where('product_id',$idProduct)->where('user_id1',$idUser1)->where('user_id2',$idUser2)->first();
            if(empty($conversationId)){
                if($idUser1!=$idUser2){
                    $conversation = Conversation::create([
                        'product_id'=>$idProduct,
                        'user_id1'=>$idUser1,
                        'user_id2'=>$idUser2
                    ]);
                    if(!empty($conversation)){
                        $id = Conversation::select('id')->where('product_id',$idProduct)->where('user_id1',$idUser1)->where('user_id2',$idUser2)->first();

                        return response([
                            'code'=>200,
                            'message'=>'Create Conversation Success',
                            'data'=>[
                                'conversation_id'=>$id->id,
                            ]

                        ]);
                    }
                }
            }else{
                $messages = Message::where('conversation_id',$conversationId->id)->get();
                foreach($messages as $message){
                    $sender = User::where('id', $message->sender_id)->get()->first();
                    $message->sender = [
                        'id' => $sender->id,
                        'name' => $sender->name,
                        'avatar' => $sender->avatar
                    ];
                }
                return response([
                    'code'=>200,
                    'message'=>'Success',
                    'data'=>[
                        'conversation_id'=>$conversationId->id,
                        'messages'=>$messages
                    ]
                ]);
            }
        }

        return response([
            'code' => 1002,
            'message' => 'Parameter is no enough',
        ]);
    }
    public function setMessageConversation(Request $request){
        $idConversation = $request->input('conversation_id');
        $idSender = $request->input('user_id');
        $content = $request->input('message');
        if(!empty($idConversation)&&!empty($idSender)&&!empty($content)){
            $conversation = Conversation::find($idConversation);
            $product = Product::find($conversation->product_id);
            $user = User::find($idSender);
            $notification = Notification::where('product_id',$conversation->product_id)->where('type',2)->where('from_id',$idSender)->get();
            if(!empty($notification)){
                foreach ($notification as $value)
                $result = $value->delete();
            }
            if($idSender ==$product->seller_id){
                $customer = User::find($conversation->user_id2);
                $token = FCMToken::find($customer->id);
                $key = $token->token;
                $notifi = Notification::create([
                    'product_id'=>$product->id,
                    'title'=>$user->name.' đã nhắn tin về '.$product->name.' của '.$user->name,
                    'type'=>2,
                    'from_id'=>$idSender,
                    'to_id'=>$customer->id,
                     'read'=>0
                ]);
                $notifi->is_seller=0;
                $msg = array(
                    'body' => $user->name.' đã nhắn tin về '.$product->name.' của '.$user->name,
                    'title' => 'Moki',
                    'icon' => 'myicon',
                    'sound' => 1,

                );
                app('App\Http\Controllers\NotificationController')->pushNotification($key, $msg,json_encode($notifi));

            }else{

                $notifi = Notification::create([
                    'product_id'=>$product->id,
                    'title'=>$user->name.' đã nhắn tin về '.$product->name.' của bạn',
                    'type'=>2,
                    'from_id'=>$idSender,
                    'to_id'=>$product->seller_id,
                    'read'=>0
                ]);
                $notifi->is_seller = 1;
                $token = FCMToken::find($product->seller_id);
                $key = $token->token;
                $msg = array(
                    'body' => $user->name.' đã nhắn tin về '.$product->name.' của bạn',
                    'title' => 'Moki',
                    'icon' => 'myicon',
                    'sound' => 1
                );
                app('App\Http\Controllers\NotificationController')->pushNotification($key, $msg,json_encode($notifi));

            }

            $message = Message::create([
                'conversation_id'=>$idConversation,
                'sender_id'=>$idSender,
                'message'=>$content
            ]);
            if(!empty($message)){
                return response([
                    'code'=>200,
                    'message'=>'Chat Success',
                ]);
            }
        }

        return response([
            'code' => 1002,
            'message' => 'Parameter is no enough',
        ]);
    }
}
