<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wechat extends Model
{
    protected $fillable = [
        'name',
        'wechat_num',
        'collect_num',
        'site_id',
        'navigation_id',
    ];
}
