<?php
namespace App\Repositories\Eloquent\criteria;

use App\Repositories\criteria\ICriterion;

class WithTrashed implements ICriterion
{

    public function apply($model)
    {
        return $model->withTrashed();
    }
}
