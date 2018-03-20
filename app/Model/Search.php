<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    protected $table = 'search';
    protected $fillable = ['user_id', 'keyword', 'category_id', 'brand_id', 'product_size_id', 'price_min', 'price_max', 'condition'];
}
