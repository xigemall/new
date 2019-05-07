<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blogroll extends Model
{
    protected $fillable = [
        'title',
        'link',
        'place',
    ];

    public function blogrollSites()
    {
        return $this->hasMany(BlogrollSite::class);
    }
}
