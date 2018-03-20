<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Ship extends Model
{
    protected $table = 'ship';
    protected $fillables = ['location_id', 'fee', 'product_id', 'leatime', 'cod', 'pay_type'];
}
