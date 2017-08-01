<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\ExpenseRequest;
use App\Http\Requests\ExpenseBuyRequest;
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

    public function index(Request $request)
    {
        $event = $this->eventRepository->findOrFail($request->event_id);

        if ($this->user->cant('view', $event)) {
            throw new UnknowException('You can not see expenses in this event');
        }

        $isManager = $this->user->can('manage', $event);
        $expenses = $event
            ->expenses()
            ->with([
                'goal.donationType.quality',
                'products',
                'qualitys',
            ])
            ->orderBy('time', 'desc')
            ->paginate(config('settings.expense.paginate_list'));

        return $this->getData(function () use ($expenses, $isManager) {
            $this->compacts['expenses'] = $expenses;
            $this->compacts['isManager'] = $isManager;
        });
    }

    public function store(ExpenseRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = $this->user->id;
        $event = $this->eventRepository->findOrFail($data['event_id']);

        if ($this->user->cant('manage', $event)) {
            throw new UnknowException('Have error when create expense');
        }

        return $this->doAction(function () use ($data, $event) {
            $this->compacts['expense'] = $this->expenseRepository->create($data);
        });
    }

    public function createBy(ExpenseBuyRequest $request)
    {
        $data = $request->all();
        $data['expense']['user_id'] = $this->user->id;
        $event = $this->eventRepository->findOrFail($data['expense']['event_id']);

        if ($this->user->cant('manage', $event)) {
            throw new UnknowException('Have error when create expense');
        }

        return $this->doAction(function () use ($data, $event) {
            $this->compacts['expense'] = $this->expenseRepository->createExpenseBuy($data);
        });
    }

    public function update(ExpenseRequest $request, $id)
    {
        $data = $request->all();
        $event = $this->eventRepository->findOrFail($data['event_id']);
        $expense = $this->expenseRepository->findOrFail($id);

        if ($this->user->cant('manage', $event)) {
            throw new UnknowException('Have error when update expense');
        }

        return $this->doAction(function () use ($id, $data) {
            $this->compacts['expense'] = $this->expenseRepository->update($id, $data);
        });
    }

    public function updateExpenseBuy(ExpenseBuyRequest $request, $id)
    {
        $data = $request->all();
        $event = $this->eventRepository->findOrFail($data['expense']['event_id']);
        $expense = $this->expenseRepository->findOrFail($id);

        if ($this->user->cant('manage', $event)) {
            throw new UnknowException('Have error when update expense');
        }

        return $this->doAction(function () use ($id, $data) {
            $this->compacts['expense'] = $this->expenseRepository->updateExpenseBuy($id, $data);
        });
    }

    public function destroy($id)
    {
        $expense = $this->expenseRepository->findOrFail($id);
        $event = $expense->event;

        if ($this->user->cant('manage', $event)) {
            throw new UnknowException('Have error when delete expense');
        }

        return $this->doAction(function () use ($expense) {
            $this->compacts['expense'] = $this->expenseRepository->delete($expense);
        });
    }

    public function statistic($eventId)
    {
        $event = $this->eventRepository->findOrFail($eventId);

        return $this->getData(function () use ($event) {
            $this->compacts['goal'] = $event->goals->each(function($goal) {
                $goal->makeVisible(['calculate'])->load('donationType.quality');
            });
        });
    }

    public function getList(Request $request)
    {
        $event = $this->eventRepository->findOrFail($request->get('event_id'));

        return $this->getData(function () use ($request, $event) {
            $expenses = $event
                ->expenses()
                ->with('goal.donationType')
                ->orderBy($request->get('order_by'), 'desc');

            if ($request->has('goal_id')) {
                $expenses->where('goal_id', $request->goal_id);
            }

            $this->compacts['expenses'] = $expenses->take(config('settings.pagination.expense_statistic'))->get();
        });
    }

    public function getStatisticData(Request $request)
    {
        $event = $this->eventRepository->findOrFail($request->get('event_id'));
        $query = $event
            ->expenses()
            ->select(\DB::raw('count(*) as count, time, sum(cost) as cost'))
            ->groupBy('time');

        if ($request->has('goal_id')) {
            $query->where('goal_id', $request->goal_id);
        }

        $statistic = new \stdClass();
        $locale = \App::getLocale();
        \Carbon\Carbon::setLocale($locale);

        $statistic->time = $query->pluck('time')->map(function($time) {
            return \Carbon\Carbon::parse($time)->toDateString();
        });

        $statistic->cost = $query->pluck('cost');
        $statistic->count = $query->pluck('count');

        return $this->getData(function () use ($event, $request, $statistic) {
            $this->compacts['statistic'] = $statistic;
        });
    }
}
