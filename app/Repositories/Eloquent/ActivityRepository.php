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

    public function getNewsFeed($campaignIds, $eventIds)
    {
        $this->setGuard('api');
        $friendIds = $this->user->friends()->pluck('id')->all();
        $friendIds[] = $this->user->id;
        $infoPaginate = $this->whereIn('activitiable_type', [
            Campaign::class,
            Event::class,
        ])
        ->where('name', Activity::CREATE)
        ->where(function ($query) use ($campaignIds, $eventIds) {
            return $query->where(function ($query) use ($eventIds) {
                    return $query->whereIn('activitiable_id', $eventIds)
                        ->where('activitiable_type', Event::class);
                })
                ->orWhere(function ($sub) use ($campaignIds) {
                    return $sub->where('activitiable_type', Campaign::class)
                        ->whereIn('activitiable_id', $campaignIds);
                });
        })
        ->whereIn('user_id', $friendIds)
        ->with('user')
        ->orderBy('created_at', 'DESC')
        ->paginate(config('settings.pagination.homepage'));

        $listActivity = $infoPaginate->each(function ($item) {
            if ($item->activitiable_type == Campaign::class) {
                $item->load('activitiable.tags', 'activitiable.media' );
            } else {
                $item->load('activitiable.campaign', 'activitiable.media');
            }
        });

        return [
            'infoPaginate' => $infoPaginate,
            'data' => $listActivity,
        ];
    }
}
