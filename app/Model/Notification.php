<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notification';
    protected $fillable = ['title', 'object_id', 'type', 'group', 'read', 'from_id', 'to_id', 'content'];
}
