<?php

namespace App\Repositories\Eloquent;

use App\design;
use App\Repositories\Contracts\IDesign;

Class DesignRepository implements IDesign
{

    public function all()
    {
        return design::all();
    }
}
