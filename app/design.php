<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class design extends Model
{
    protected $fillable = [
        'user_id',
        'image',
        'title',
        'description',
        'slug',
        'is_live',
        'close_to_comment',
        'upload_successful',
        'disk',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getImagesAttribute()
    {
        return [
            'thumbnail' => $this->getImagePath('thumbnail'),
            'large' => $this->getImagePath('large'),
            'original' => $this->getImagePath('original'),
        ];
    }

    public function getImagePath($size)
    {
        return Storage::disk($this->disk)->url("uploads/designs/{$size}/".$this->image);
    }
}
