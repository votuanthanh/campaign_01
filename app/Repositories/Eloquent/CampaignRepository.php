<?php

namespace App\Repositories\Eloquent;

use App\Models\Media;
use App\Models\Campaign;
use App\Traits\Common\UploadableTrait;
use App\Repositories\Contracts\CampaignInterface;
use App\Exceptions\Api\NotFoundException;
use App\Exceptions\Api\UnknowException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class CampaignRepository extends BaseRepository implements CampaignInterface
{
    use UploadableTrait;

    public function model()
    {
        return Campaign::class;
    }

    private function isArrayFormat($settings)
    {
        if (!$settings) {
            return false;
        }

        if (!is_array($settings)) {
            throw new UnknowException('Param is not an array');
        }

        //check each element is array
        foreach ($settings as $setting) {
            if (!is_array($setting)) {
                throw new UnknowException('Invalid format array');
            }
        }

        return true;
    }

    public function create($inputs)
    {
        if (is_null($inputs) || !is_array($inputs)) {
            throw new UnknowException('Inputs is null or not an array');
        }

        $data = [
            'title' => $inputs['title'],
            'hashtag' => $inputs['hashtag'],
            'description' => $inputs['description'],
            'longitude' => $inputs['longitude'],
            'latitude' => $inputs['latitude'],
            'status' => Campaign::ACTIVE,
        ];

        $campaign = parent::create($data);

        if (!$campaign) {
            throw new NotFoundException('Error create campaign');
        }

        if (array_key_exists('settings', $inputs) && $this->isArrayFormat($inputs['settings'])) {
            $campaign->settings()->createMany($inputs['settings']);
        }

        if (!empty($inputs['media']) && is_file($inputs['media'])) {
            $urlFile = $this->uploadFile($inputs['media'], 'campaigns');
            $campaign->media()->create([
                'url_file' => $urlFile,
                'type' => Media::IMAGE,
            ]);
        }

        $campaign->users()->attach($inputs['user_id'], [
            'role_id' => $inputs['role_id'],
        ]);

        if ($inputs['tags'] && is_array($inputs['tags'])) {
            $campaign->tags()->attach($inputs['tags']['old']);
            $campaign->tags()->createMany($inputs['tags']['new']);
        }

        return true;
    }

    public function update($campaign, $inputs)
    {
        $this->deleteOrCreateTags($campaign, $inputs['tags']);
        $this->updateSettings($campaign, $inputs['settings']);
        $this->updateMedia($inputs['media'], $campaign);
        $campaign = parent::update($campaign->id, array_except($inputs, ['tags', 'settings', 'media']));

        return $campaign;
    }

    private function deleteOrCreateTags($campaign, $tags)
    {
        if (!$this->isArrayFormat($tags)) {
            return false;
        }

        if (!$campaign->tags->isEmpty() && !$tags) {
            $campaign->tags()->detach($campaign->tags->pluck('id'));
        }

        if ($campaign->tags->isEmpty() && !$tags) {
            return false;
        }

        $oldIds = [];
        $newTags = [];

        foreach ($tags as $tag) {
            if ($tag['id']) {
                $oldIds[] = $tag['id'];
            } else {
                $newTags[] = ['name' => $tag['name']];
            }
        }

        $deleteIds = array_diff($campaign->tags->pluck('id')->toArray(), $oldIds);

        if ($deleteIds) {
            $campaign->tags()->detach($deleteIds);
        }

        if ($newTags) {
            $campaign->tags()->createMany($newTags);
        }

        return true;
    }

    private function updateSettings($campaign, $settings)
    {
        if (!$campaign || !$this->isArrayFormat($settings)) {
            return false;
        }

        $settingsCampaign = $campaign->settings;

        if ($settingsCampaign->isEmpty()) {
            return false;
        }

        foreach ($settings as $name => $setting) {
            $model = $settingsCampaign->where('key', $setting['key'])->first();

            if ($model) {
                $model->update(['value' => $setting['value']]);
            }
        }

        return true;
    }

    private function updateMedia($campaign, $media)
    {
        if (!$campaign || !is_file($media)) {
            return false;
        }

        $model = $campaign->media->first();

        if (!$model) {
            return false;
        }

        $this->destroyFile($model->url_file);
        $urlFile = $this->uploadFile($media, 'campaigns');
        $model->update(['url_file' => $urlFile]);

        return true;
    }

    public function delete($campaign)
    {
        $campaign->donations()->delete();
        $campaign->tags()->detach();
        $campaign->users()->detach();
        $campaign->media()->delete();
        $campaign->likes()->delete();
        $campaign->settings()->delete();

        return $campaign->delete();
    }

    /**
     * get campaign timeline.
     *
     * @param  array  $data
     * @return $campaign
    */
    public function getCampaignTimeline($campaign)
    {
        return $campaign->with('media', 'settings', 'tags')->get();
    }

    /**
     * get getListUser.
     *
     * @param  array  $data
     * @return $campaign
    */
    public function getListUser($campaign)
    {
        return $campaign->with('users')->get();
    }
}
