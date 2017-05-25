<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;
use App\Models\Campaign;

class EventPolicy extends BasePolicy
{
    /**
     * Determine whether the user can create events.
     *
     * @param  \App\Models\User  $user
     * @param \App\Models\Campaign  $campaign
     * @return mixed
     */
    public function createEvent(User $user, Campaign $campaign)
    {
        return in_array($user->id, $campaign->getUserByRole(['owner', 'moderator'])->get()->toArray());
    }

    /**
     * Determine whether the user can manage the event. (delete/update)
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return mixed
     */
    public function manage(User $user, Event $event)
    {
        return $user->id === $event->user_id
            || $user->can('manage', $event->campaign);
    }
}
