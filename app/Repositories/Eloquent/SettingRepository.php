<?php

namespace App\Repositories\Eloquent;

use App\Models\Setting;
use App\Models\Campaign;
use App\Repositories\Contracts\SettingInterface;
use App\Exceptions\Api\NotFoundException;

class SettingRepository extends BaseRepository implements SettingInterface
{
    public function model()
    {
        return Setting::class;
    }

    public function getCampaignIds()
    {
        $this->setGuard('api');
        $campaignIds = $this->user->campaigns
            ->where('campaigns.status', Campaign::ACTIVE)
            ->where('pivot.status', Campaign::APPROVED)
            ->pluck('campaigns.id')
            ->all();
        $campaignsClose = $this->user->campaigns
            ->where('status', Campaign::BLOCK)
            ->pluck('id')
            ->all();

        $campaignsPublic = $this->where('settingable_type', Campaign::class)
            ->where('key', config('settings.campaigns.status'))
            ->where('value', config('settings.value_of_settings.status.public'))
            ->whereNotIn('settingable_id', $campaignsClose)
            ->groupBy('settingable_id')
            ->pluck('settingable_id')
            ->all();

        return array_unique(array_merge($campaignIds, $campaignsPublic));
    }
}
