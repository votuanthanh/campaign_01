<?php

namespace App\Repositories\Eloquent;

use App\Models\Comment;
use App\Models\Activity;
use App\Exceptions\Api\UnknowException;
use App\Repositories\Contracts\CommentInterface;

class CommentRepository extends BaseRepository implements CommentInterface
{
    public function model()
    {
        return Comment::class;
    }

    public function createComment($data, $model)
    {
        if (empty($data) || empty($model)) {
            throw new UnknowException('Data is null');
        }

        $comment = $model->comments()->create($data);
        $comment->activities()->create([
            'user_id' => $data['user_id'],
            'name' => Activity::CREATE,
        ]);

        return $comment;
    }
}
