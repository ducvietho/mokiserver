<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'setting';

    protected $hidden = ['id'];

    protected $fillable = ['user_id', 'like', 'transaction', 'announcement', 'comment',
        'sound_on', 'sound_default', 'auto_with_draw', 'vacation_mode'];
}
