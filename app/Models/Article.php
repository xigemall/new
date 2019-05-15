<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'name',
        'wechat_num',
        'site_id',
        'navigation_id',
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
        'comments' => 'array',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function navigation()
    {
        return $this->belongsTo(Navigation::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }
}
