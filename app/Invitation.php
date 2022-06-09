<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = ['sender_id','team_id','token','recipient_email'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function recipient()
    {
        return $this->hasOne(User::class,'email','recipient_email');
    }

    public function sender()
    {
        return $this->hasOne(User::class,'id','sender_id');
    }
}
