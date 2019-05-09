<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteNavigationArticle extends Model
{
    protected $fillable = [
      'site_id',
      'navigation_id',
      'article_id',
    ];
}
