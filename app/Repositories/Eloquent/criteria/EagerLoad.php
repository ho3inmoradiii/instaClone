<?php
namespace App\Repositories\Eloquent\criteria;

use App\Repositories\criteria\ICriterion;

class EagerLoad implements ICriterion
{
    protected $rel;

    public function __construct($rel)
    {
        $this->rel = $rel;
    }

    public function apply($model)
    {
        return $model->with($this->rel);
    }
}
