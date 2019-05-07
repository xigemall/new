<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
      'name',
      'account',
      'type',
      'author',
      'image_url',
      'original_url',
      'audio_url',
      'update_time',
      'title',
      'summary',
      'public_time',
      'info_url',
      'is_original',
      'read_num',
      'like_num',
      'content',
      'keywords',
    ];
}
