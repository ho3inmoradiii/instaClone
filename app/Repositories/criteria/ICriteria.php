<?php
namespace App\Repositories\criteria;

interface ICriteria
{
    public function withCriteria(...$criteria);
}
