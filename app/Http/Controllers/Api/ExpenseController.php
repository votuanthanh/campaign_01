<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\ExpenseRequest;
use App\Repositories\Contracts\ExpenseInterface;
use App\Repositories\Contracts\EventInterface;

class ExpenseController extends ApiController
{
    protected $expenseRepository;
    protected $eventRepository;

    public function __construct(
        ExpenseInterface $expenseRepository,
        EventInterface $eventRepository
    ) {
        parent::__construct();
        $this->expenseRepository = $expenseRepository;
        $this->eventRepository = $eventRepository;
    }

    public function store(ExpenseRequest $request)
    {
        $data = $request->get('type');
        $event = $this->eventRepository->findOrFail($data['event_id']);

        if ($this->user->cant('manage', $event)) {
            throw new UnknowException('Have error when create expense');
        }

        return $this->doAction(function () use ($data, $event) {
            $this->compacts['expense'] = $this->expenseRepository->multiCreate($data);
        });
    }

    public function update(ExpenseRequest $request, $id)
    {
        $data['expense'] = $request->only(
            'user_id',
            'event_id',
            'goal_id',
            'time',
            'cost',
            'reason',
            'type'
        );

        $data['quality'] = $request->get('quality');
        $data['name'] = $request->get('name');
        $data['quantity'] = $request->get('quantity');

        $event = $this->eventRepository->findOrFail($data['expense']['event_id']);

        if ($this->user->cant('manage', $event)) {
            throw new UnknowException('Have error when update expense');
        }

        return $this->doAction(function () use ($data, $event, $id) {
            $this->compacts['expense'] = $this->expenseRepository->update($id, $data);
        });
    }

    public function destroy($id)
    {
        $expense = $this->expenseRepository->findOrFail($id);
        $event = $expense->event;

        if ($this->user->cant('manage', $event)) {
            throw new UnknowException('Have error when delete expense');
        }

        return $this->doAction(function () use ($event, $id, $expense) {
            $this->compacts['expense'] = $this->expenseRepository->delete($expense);
        });
    }
}
