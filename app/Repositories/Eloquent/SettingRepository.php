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

    public function getCampaignPublicIds() {
        return $this->where('settingable_type', Campaign::class)
            ->where('key', config('settings.campaigns.status'))
            ->where('value', config('settings.value_of_settings.status.public'))
            ->groupBy('settingable_id')
            ->pluck('settingable_id')
            ->all();
    }
}
