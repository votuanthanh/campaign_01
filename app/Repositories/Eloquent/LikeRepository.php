<?php

namespace App\Repositories\Eloquent;

use App\Models\Like;
use App\Models\Activity;
use App\Exceptions\Api\UnknowException;
use App\Repositories\Contracts\LikeInterface;
use Illuminate\Support\Facades\Event;

class LikeRepository extends BaseRepository implements LikeInterface
{
    public function model()
    {
        return Like::class;
    }

    public function likeOrUnlike($model)
    {
        if (!$this->user || !$model) {
            return false;
        }

        if ($model->likes->where('user_id', $this->user->id)->isEmpty()) {
            $like = $model->likes()->create([
                'user_id' => $this->user->id
            ]);

            Event::fire('add.activity', [
                [
                    'model' => $like,
                    'user_id' => $this->user->id,
                    'action' => Activity::CREATE
                ]
            ]);

            return $like->setAttribute('user', $this->user);
        }

        $this->where('user_id', $this->user->id)
            ->where('likeable_id', $model->id)
            ->where('likeable_type', get_class($model))
            ->activities()
            ->first()
            ->delete();

        return $model->likes()->where('user_id', $this->user->id)->first()->forceDelete();
    }
}
