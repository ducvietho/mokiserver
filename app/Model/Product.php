<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $fillable = ['seller_id', 'name', 'price', 'price_new', 'price_percent', 'product_size_id', 'dimension', 'offer', 'weight', 'status_code',
        'brand_id', 'category_id', 'image', 'video', 'thumb', 'described', 'ships_from', 'ships_from', 'ships_from_id', 'condition', 'is_sold'];

    public function banned()
    {
        return $this->belongsToMany('App\Model\Campaign', 'product_campaign', 'product_id', 'campaign_id');
    }
}
