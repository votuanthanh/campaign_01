<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\ActionInterface;
use App\Models\Action;

class ActionRepository extends BaseRepository implements ActionInterface
{
    public function model()
    {
        return Action::class;
    }

    public function createOrDeleteLike($action, $userId)
    {
        if (!is_numeric($userId) || !$action) {
            return false;
        }

        if ($action->likes->where('user_id', $userId)->isEmpty()) {
            return $this->createByRelationship('likes', [
                'model' => $action,
                'attribute' => ['user_id' => $userId],
            ]);
        }

        return $action->likes()->where('user_id', $userId)->first()->forceDelete();
    }
}
