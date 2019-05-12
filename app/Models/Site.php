<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $fillable = [
        'title',
        'description',
        'keyword',
        'domain',
        'logo',
        'ico',
        'template_id',
        'visit',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function navigations()
    {
        return $this->hasMany(Navigation::class);
    }

    public function articles($navigationId = 0)
    {
        if ($navigationId) {
            return $this->hasMany(Article::class)->where('navigation_id', $navigationId);
        } else {
            return $this->hasMany(Article::class);
        }

    }
}
