<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $fillable = ['seller_id', 'name', 'price',  'dimension',  'weight', 'status',
         'category_id', 'image', 'address', 'described', 'ships_from', 'condition', 'is_sold'];


}
