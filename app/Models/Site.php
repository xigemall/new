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

    /**
     * 模板关联
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * 栏目关联
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function navigations()
    {
        return $this->hasMany(Navigation::class);
    }

    /**
     * 网站、栏目文章关联
     */
    public function SiteNavigationArticles()
    {
        $this->hasMany(SiteNavigationArticle::class);
    }

    public function getLogoAttribute($value)
    {
        return config('app.url') . '/' . $value;
    }

    public function getIcoAttribute($value)
    {
        return config('app.url') . '/' . $value;
    }
}
