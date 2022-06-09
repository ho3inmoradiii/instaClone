<?php

namespace App\Repositories\Eloquent;

use App\Chat;
use App\Repositories\Contracts\IChat;
use App\Repositories\Eloquent\BaseRepository;

Class ChatRepository extends BaseRepository implements IChat
{
    public function model()
    {
        return Chat::class;
    }
}
