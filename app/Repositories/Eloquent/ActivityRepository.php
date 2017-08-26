<?php

namespace App\Repositories\Eloquent;

use Exception;
use App\Models\Activity;
use App\Models\Campaign;
use App\Models\Event;
use App\Exceptions\Api\UnknowException;
use App\Repositories\Contracts\ActivityInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ActivityRepository extends BaseRepository implements ActivityInterface
{
    public function model()
    {
        return Activity::class;
    }

    public function getHomePage()
    {
        $this->setGuard('api');
        $friendIds = $this->user->friends()->pluck('id')->all();
        $friendIds[] = $this->user->id;
        $listActivity = $this->whereIn('activitiable_type', [
                Campaign::class,
                Event::class,
            ])
            ->where('name', Activity::CREATE)
            ->whereIn('user_id', $friendIds)
            ->with('activitiable.media', 'user')
            ->orderBy('created_at', 'DESC')
            ->paginate(config('setting.pagination.homepage'));

        return $listActivity;
    }
}
