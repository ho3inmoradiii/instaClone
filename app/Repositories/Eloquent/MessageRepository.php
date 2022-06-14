<?php

namespace App\Repositories\Eloquent;

use App\Chat;
use App\Message;
use App\Repositories\Contracts\IChat;
use App\Repositories\Contracts\IMessage;
use App\Repositories\Eloquent\BaseRepository;

Class MessageRepository extends BaseRepository implements IMessage
{
    public function model()
    {
        return Message::class;
    }
}
