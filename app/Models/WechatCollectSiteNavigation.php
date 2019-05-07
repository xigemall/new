<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WechatCollectSiteNavigation extends Model
{
    protected $fillable = [
        'site_id',
        'navigation_id',
        'wechat_id',
    ];
}
