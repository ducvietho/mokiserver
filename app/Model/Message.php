<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'message';
    protected $fillable = ['conversation_id', 'sender_id', 'message', 'read'];

    public function sender()
    {
        return $this->belongsTo('App\Model\User', 'sender_id');
    }
}
