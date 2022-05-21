<?php
namespace App\Repositories\Eloquent\criteria;

use App\Repositories\criteria\ICriterion;

class IsLive implements ICriterion
{

    public function apply($model)
    {
        return $model->where('is_live',true);
    }
}
