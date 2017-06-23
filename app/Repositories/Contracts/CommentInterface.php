<?php

namespace App\Repositories\Contracts;

interface CommentInterface extends RepositoryInterface
{
    public function createComment($data, $model);
}
