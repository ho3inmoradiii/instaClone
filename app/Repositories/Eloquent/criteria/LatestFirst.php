<?php
namespace App\Repositories\Eloquent\criteria;

use App\Repositories\criteria\ICriterion;

class LatestFirst implements ICriterion
{

    public function apply($model)
    {
        return $model->latest();
    }
}
