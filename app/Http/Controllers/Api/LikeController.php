<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CommentRequest;
use App\Exceptions\Api\UnknowException;
use App\Exceptions\Api\NotFoundException;
use App\Repositories\Contracts\EventInterface;
use App\Repositories\Contracts\ActionInterface;
use App\Repositories\Contracts\CommentInterface;
use App\Repositories\Contracts\CampaignInterface;
use App\Repositories\Contracts\LikeInterface;
use Illuminate\Http\Request;

class LikeController extends ApiController
{
    protected $campaignRepository;
    protected $eventRepository;
    protected $actionRepository;
    protected $commentRepository;

    public function __construct(
        CampaignInterface $campaignRepository,
        EventInterface $eventRepository,
        ActionInterface $actionRepository,
        CommentInterface $commentRepository,
        LikeInterface $likeRepository
    ) {
        parent::__construct();
        $this->campaignRepository= $campaignRepository;
        $this->eventRepository = $eventRepository;
        $this->actionRepository = $actionRepository;
        $this->commentRepository = $commentRepository;
        $this->likeRepository = $likeRepository;
    }

    public function like($modelId, $flag)
    {
        if (!$modelId) {
            throw new UnknowException('modelId is null');
        }

        $userId = $this->user->id;

        if ($flag) {
            switch ($flag) {
                case 'campaign':
                    $model = $this->campaignRepository->findOrFail($modelId);
                    break;
                case 'event':
                    $model = $this->eventRepository->findOrFail($modelId);
                    break;
                case 'action':
                    $model = $this->actionRepository->findOrFail($modelId);
                    break;
                case 'comment':
                    $model = $this->commentRepository->findOrFail($modelId);
                    break;
                default:
                    $model = '';
            }
        }

        if ($this->user->cant('view', $model)) {
            throw new UnknowException('Permission error: User can not like in this post.');
        }

        return $this->doAction(function () use ($model, $userId) {
            $this->compacts['like'] = $this->likeRepository->likeOrUnlike($model, $userId);
        });
    }
}
