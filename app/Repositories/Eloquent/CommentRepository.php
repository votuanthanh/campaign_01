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

    public function getComment($modelId)
    {
        return $this->with('user')->where('parent_id', config('settings.comment_parent'))
            ->where('commentable_id', $modelId)->orderBy('created_at', 'desc')
            ->paginate(config('settings.paginate_comment'));
    }

    public function getSubComment($id)
    {
        $comment = $this->findOrFail($id);

        return $comment->subComment()
           ->with('user')
           ->orderBy('created_at', 'desc')
           ->paginate(config('settings.paginate_comment'));
    }

    public function delete($comment)
    {
        $comment->activities()->delete();
        $comment->likes()->delete();
        $comment->subComment()->delete();

        return $comment->delete();
    }
}
