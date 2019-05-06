<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertisingSite extends Model
{
    protected $fillable = [
        'advertising_id',
        'site_id',
    ];

    public $timestamps = false;

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
