<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $fillable = ['seller_id', 'name', 'price', 'product_size_id', 'dimension', 'offer', 'weight', 'status_code',
        'brand_id','status', 'category_id', 'image', 'described', 'ships_from','is_sold'];

    public function banned()
    {
        return $this->belongsToMany('App\Model\Campaign', 'product_campaign', 'product_id', 'campaign_id');
    }
}
