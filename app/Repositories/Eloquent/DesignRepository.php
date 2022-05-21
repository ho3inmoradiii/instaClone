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
}
