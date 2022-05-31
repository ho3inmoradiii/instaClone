<?php

namespace App\Repositories\Eloquent;

use App\design;
use App\Repositories\Contracts\IDesign;
use App\Repositories\Eloquent\BaseRepository;

Class DesignRepository extends BaseRepository implements IDesign
{
    public function model()
    {
        return design::class;
    }

    public function applyTag($id,array $data)
    {
        $design = $this->find($id);
        $design->retag($data);
    }

    public function addComment($id, array $data)
    {
        //get the design that we want to create comment for that
        $design = $this->find($id);

        //create comment
        $comment = $design->comments()->create($data);

        return $comment;
    }

    public function like($id)
    {
        $design = $this->find($id);
        if ($design->isLikedByUser(auth()->id())){
            $design->unlike();
        }else{
            $design->like();
        }
    }

    public function isLikedByUser($designId)
    {
        $design = $this->find($designId);
        return $design->isLikedByUser(auth()->id());
    }
}
