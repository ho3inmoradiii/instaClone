<?php

namespace App;

use App\Traits\likeable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Cviebrock\EloquentTaggable\Taggable;

class design extends Model
{
    use Taggable,likeable;

    protected $fillable = [
        'user_id',
        'image',
        'team_id',
        'title',
        'description',
        'slug',
        'is_live',
        'close_to_comment',
        'upload_successful',
        'disk',
    ];

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

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable')->orderBy('created_at','asc');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
