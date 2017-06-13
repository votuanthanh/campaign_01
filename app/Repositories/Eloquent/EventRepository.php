<?php

namespace App\Repositories\Eloquent;

use App\Models\Event;
use App\Repositories\Contracts\EventInterface;

class EventRepository extends BaseRepository implements EventInterface
{
    public function model()
    {
        return Event::class;
    }

    public function delete($eventIds)
    {
        if (!$eventIds) {
            $events = $this->model->whereIn('id', $eventIds);
            $events->goals()->delete();
            $events->action()->delete();

            return $events->delete();
        }
    }
}
