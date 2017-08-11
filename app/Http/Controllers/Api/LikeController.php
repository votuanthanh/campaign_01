<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CommentRequest;
use App\Exceptions\Api\UnknowException;
use App\Exceptions\Api\NotFoundException;
use App\Repositories\Contracts\LikeInterface;
use Illuminate\Http\Request;

class LikeController extends ApiController
{
    protected $likeRepository;

    public function __construct(LikeInterface $likeRepository)
    {
        parent::__construct($likeRepository);
        $this->likeRepository = $likeRepository;
    }

    public function like($modelId, $flag)
    {
        $likeClass = new \ReflectionClass($this->likeRepository);
        $model = app($likeClass->getNamespaceName() . '\\' . ucfirst($flag . 'Repository'))->findOrFail($modelId);

        if ($this->user->cant('view', $model)) {
            throw new UnknowException('Permission error: User can not like in this post.');
        }

        return $this->doAction(function () use ($model) {
            $this->compacts['like'] = $this->likeRepository->likeOrUnlike($model, $this->user);
        });
    }
}
