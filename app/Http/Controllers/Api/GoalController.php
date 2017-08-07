<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Repositories\Contracts\EventInterface;

class GoalController extends ApiController
{
    protected $eventRepository;

    public function __construct(
        EventInterface $eventRepository
    ) {
        parent::__construct();
        $this->eventRepository = $eventRepository;
    }

    public function index(Request $request)
    {
        $event = $this->eventRepository->findOrFail($request->event_id);

        if ($this->user->cant('view', $event)) {
            throw new UnknowException('You can not see goal in this event');
        }

        $goals = $event
            ->goals()
            ->select('id', 'donation_type_id', 'goal')
            ->with([
                'donations' => function ($query) {
                    return $query->with('user')->latest();
                },
                'donationType.quality',
                'expenses.products',
            ])
            ->get();

        return $this->doAction(function () use ($goals) {
            $this->compacts['goals'] = $goals;
        });
    }
}
