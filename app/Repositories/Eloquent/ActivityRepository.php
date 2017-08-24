<?php

namespace App\Repositories\Eloquent;

use Exception;
use App\Models\Activity;
use App\Models\Action;
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
        $friendIds = $this->user->friends()->pluck('id');
        $listActivity = $this->where('activitiable_type', '<>', Action::class)
            ->where('name', Activity::CREATE)
            ->whereIn('user_id', $friendIds)
            ->with('activitiable.media', 'user')
            ->paginate(config('setting.pagination.homepage'));

        return $listActivity;
    }
}
