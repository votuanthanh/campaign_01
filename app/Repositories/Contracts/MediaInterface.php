<?php

namespace App\Repositories\Contracts;

interface MediaInterface extends RepositoryInterface
{
    public function deleteMedia($media);
}
