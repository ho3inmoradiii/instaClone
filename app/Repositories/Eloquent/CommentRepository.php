<?php

namespace App\Repositories\Eloquent;

use App\Comment;
use App\Repositories\Contracts\IComment;
use App\Repositories\Eloquent\BaseRepository;

Class CommentRepository extends BaseRepository implements IComment
{
    public function model()
    {
        return Comment::class;
    }
}
