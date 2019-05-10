<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteNavigationArticle extends Model
{
    public $timestamps = false;
    protected $fillable = [
      'site_id',
      'navigation_id',
      'article_id',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function navigation()
    {
        return $this->belongsTo(Navigation::class);
    }
}
