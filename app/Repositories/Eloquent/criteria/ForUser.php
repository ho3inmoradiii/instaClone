<?php
namespace App\Repositories\Eloquent\criteria;

use App\Repositories\criteria\ICriterion;

class ForUser implements ICriterion
{
    protected $user_id;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    public function apply($model)
    {
        return $model->where('user_id',$this->user_id);
    }
}
