<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Campaign;

class CampaignPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the campaign.
     *
     * @param  \App\User  $user
     * @param  \App\Campaign  $campaign
     * @return mixed
     */
    public function view(User $user, Campaign $campaign)
    {
        if (in_array($user->id, $campaign->blockeds()->pluck('id')->toArray())) {
            return false;
        }

        if ($campaign->status == config('campaign.status.public')) {
            return true;
        }

        return in_array($campaign->id, $user->campaigns->pluck('id'));
    }

    /**
     * Determine whether the user can create campaigns.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        /**
         * Now, every user can create campaigns
         * but in the future, we'll change this
         * so I keep this function here
         */
    }

    /**
     * Determine whether the user can manage the campaign. (update/delete)
     *
     * @param  \App\User  $user
     * @param  \App\Campaign  $campaign
     * @return mixed
     */
    public function manage(User $user, Campaign $campaign)
    {
        return $user->id === $campaign->owner()->id;
    }
}
