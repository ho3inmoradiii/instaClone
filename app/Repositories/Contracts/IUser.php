<?php
namespace App\Repositories\Contracts;

interface IUser
{
    public function getUserByEmail($email);
}
