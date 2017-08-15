<?php

namespace App\Repositories\Contracts;

interface GoalInterface extends RepositoryInterface
{
    public function updateManyRow($data);

    public function listGoal($event);
}
