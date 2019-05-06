<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertising extends Model
{
    protected $fillable = [
        'title',
        'img',
        'link',
        'place',
    ];

    public function AdvertisingSites()
    {
        return $this->hasMany(AdvertisingSite::class);
    }
}
