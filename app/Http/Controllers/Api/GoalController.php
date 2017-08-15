<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Repositories\Contracts\EventInterface;
use App\Repositories\Contracts\GoalInterface;

class GoalController extends ApiController
{
    protected $eventRepository;
    protected $goalRepository;

    public function __construct(
        EventInterface $eventRepository,
        GoalInterface $goalRepository
    ) {
        parent::__construct();
        $this->eventRepository = $eventRepository;
        $this->goalRepository = $goalRepository;
    }

    public function index(Request $request)
    {
        $event = $this->eventRepository->findOrFail($request->event_id);

        if ($this->user->cant('view', $event)) {
            throw new UnknowException('You can not see goal in this event');
        }

        return $this->getData(function () use ($event) {
            $this->compacts['goals'] = $this->goalRepository->listGoal($event);
        });
    }

    public function destroy(Request $request, $id)
    {
        $event = $this->eventRepository->findOrFail($request->event_id);

        if ($this->user->cant('manage', $event)) {
            throw new UnknowException('You can not delete goal in this event');
        }

        return $this->doAction(function () use ($event, $id) {
            $this->compacts['deleteGoal'] = $event->goals()->findOrFail($id)->forceDelete();
        });
    }
}
