<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $table = 'order_product';
    protected $fillable = ['id','customer_id', 'product_id','address', 'type'];
    public $timestamps = false;
}
