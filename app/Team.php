<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name','owner_id','slug'
    ];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        static::created(function ($team){
            $team->members()->attach(auth()->id());
        });

        static::deleting(function ($team){
            $team->members()->sync([]);
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class,'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function designs()
    {
        return $this->hasMany(design::class);
    }

    public function hasUser(User $user)
    {
        return $this->members()->where('user_id',$user->id)->first() ? true : false;
    }

    public function invitation()
    {
        return $this->hasMany(Invitation::class);
    }

    public function hasPendingInvite($email)
    {
        return (bool)$this->invitation()->where('recipient_email',$email)->count();
    }
}