<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    protected $table = 'order_address';
    protected $fillable = ['user_id', 'province','district','village','street', 'address_id', 'default'];
}
