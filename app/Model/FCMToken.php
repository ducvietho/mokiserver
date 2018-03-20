<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FCMToken extends Model
{
    protected $table = 'fcm_token';
    protected $fillable = ['token', 'user_id'];
    public $timestamps = false;
}
