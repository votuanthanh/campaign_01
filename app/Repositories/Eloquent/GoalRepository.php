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
            ->join('donation_types', 'goals.donation_type_id', '=', 'donation_types.id')
            ->with([
                'donations' => function ($query) {
                    return $query->with('user')->latest();
                },
                'donationType.quality',
                'expenses.products',
            ])
            ->select('goals.id as goals_id', 'goals.*', 'donation_types.*')
            ->orderBy('donation_types.name')
            ->get();
    }
}
