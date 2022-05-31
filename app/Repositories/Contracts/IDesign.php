<?php
namespace App\Repositories\Contracts;

interface IDesign
{
    public function applyTag($id,array $data);
    public function addComment($id,array $data);
    public function like($id);
    public function isLikedByUser($designId);
}
