<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'name',
        'wechat_num',
        'wechat_article_id',
        'title',
        'view_count',
        'content',
        'html',
        'image_urls',
        'audio_urls',
        'video_urls',
        'comments',
    ];

    protected $casts = [
        'image_urls' => 'array',
        'audio_urls' => 'array',
        'video_urls' => 'array',
    ];
}
