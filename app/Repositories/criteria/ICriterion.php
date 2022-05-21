<?php
namespace App\Repositories\criteria;

interface ICriterion
{
    public function apply($model);
}
