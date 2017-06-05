<?php

namespace App\Repositories\Contracts;

interface QualityInterface extends RepositoryInterface
{
    public function getOrCreate($data);
}
