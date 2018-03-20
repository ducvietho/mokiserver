<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    protected $table = 'order_address';
    protected $fillable = ['user_id', 'address', 'address_id', 'default'];
}
