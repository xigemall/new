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

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
