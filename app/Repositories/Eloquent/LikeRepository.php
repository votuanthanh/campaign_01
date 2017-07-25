<?php

namespace App\Repositories\Eloquent;

use App\Models\Like;
use App\Models\Activity;
use App\Exceptions\Api\UnknowException;
use App\Repositories\Contracts\LikeInterface;

class LikeRepository extends BaseRepository implements LikeInterface
{
    public function model()
    {
        return Like::class;
    }

    public function likeOrUnlike($model, $userId)
    {
        if (!is_numeric($userId) || !$model) {
            return false;
        }

        if ($model->likes->where('user_id', $userId)->isEmpty()) {
            $like = $model->likes()->create([
                'user_id' => $userId
            ]);

            $like->activities()->create([
                'user_id' => $userId,
                'name' => Activity::CREATE,
            ]);

            return $like;
        }

        $this->where('user_id', $userId)
            ->where('likeable_id', $model->id)
            ->where('likeable_type', get_class($model))
            ->activities()
            ->first()
            ->delete();

        return $model->likes()->where('user_id', $userId)->first()->forceDelete();
    }
}
