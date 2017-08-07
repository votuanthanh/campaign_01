<?php

namespace App\Repositories\Eloquent;

use Exception;
use App\Models\Goal;

class GoalRepository extends BaseRepository implements GoalInterface
{
    use UploadableTrait;

    public function model()
    {
        return Goal::class;
    }
}
