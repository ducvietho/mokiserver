<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $fillable = ['name', 'description'];

    public function sizes()
    {
        return $this->belongsToMany('App\Model\Size', 'category_size', 'category_id', 'size_id');
    }

    public function brands()
    {
        return $this->belongsToMany('App\Model\Brand', 'category_brand', 'category_id', 'brand_id');
    }
}
