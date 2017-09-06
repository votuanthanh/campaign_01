<?php

namespace App\Repositories\Eloquent;

use Exception;
use App\Models\Goal;
use App\Repositories\Contracts\GoalInterface;

class GoalRepository extends BaseRepository implements GoalInterface
{
    public function model()
    {
        return Goal::class;
    }

    public function updateManyRow($data)
    {
        if (!is_array($data)) {
            return false;
        }

        foreach ($data as $value) {
            $this->update($value['id'], ['goal' => $value['goal']]);
        }
    }

    public function listGoal($event)
    {
        return $event
            ->goals()
            ->with([
                'donations',
                'donationType.quality',
                'expenses.products',
            ])->get();
    }

    public function getInfoGoal($goalId)
    {
        return $this->findOrFail($goalId)->load('donationType.quality');
    }

    public function getGoalFromEvent($event)
    {
        return $event->goals()
            ->withTrashed()
            ->select('id', 'donation_type_id', 'goal')
            ->with([
                'donations' => function ($query) {
                    return $query->with('user')->latest();
                },
                'donationType.quality' => function($query) {
                    $query->withTrashed();
                },
            ])
            ->get();
    }
}
