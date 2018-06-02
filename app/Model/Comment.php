<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comment';
    protected $fillable = ['product_id', 'poster_id', 'content'];
    public function poster(){
        return $this->belongsTo('App\Model\User', 'poster_id');
    }
}
