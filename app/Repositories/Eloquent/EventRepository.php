<?php

namespace App\Repositories\Eloquent;

use Exception;
use App\Models\Event;
use App\Models\Media;
use App\Traits\Common\UploadableTrait;
use App\Exceptions\Api\UnknowException;
use App\Exceptions\Api\NotFoundException;
use App\Repositories\Contracts\EventInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EventRepository extends BaseRepository implements EventInterface
{
    use UploadableTrait;

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

    public function create($data)
    {
        $donation = $data['donations'];
        $dataSettings = $this->createDataSettings($data['other']['settings']);
        $dataMedias = $this->createDataMedias($data['other']['files']);
        $event = $this->model->create($data['data_event']);

        if (!$event) {
            throw new NotFoundException('Have error when create model');
        }

        $createSettings = $event->settings()->createMany($dataSettings);

        if (!$createSettings) {
            throw new NotFoundException('Have error when create model');
        }

        $createMedias = $event->media()->createMany($dataMedias);

        if (!$createMedias) {
            throw new NotFoundException('Have error when create model');
        }

        if ($donation) {
            $goals = $event->goals()->createMany($donation);

            if (!$goals) {
                throw new NotFoundException('Have error when create model');
            }
        }

        return true;
    }

    private function createDataSettings($data)
    {
        if (!is_array($data)) {
            throw new UnknowException('Data type is incorrect');
        }

        foreach ($data as $key => $value) {
            $result[] = [
                'key' => $key,
                'value' => $value,
            ];
        }

        return $result;
    }

    private function createDataMedias($data)
    {
        if (!is_array($data)) {
            throw new UnknowException('Data type is incorrect');
        }

        foreach ($data as $value) {
            $urlFile = $this->uploadFile($value, 'event');

            if (!$urlFile) {
                throw new UnknowException('Upload file fail');
            }

            $result[] = [
                'type' => Media::IMAGE,
                'url_file' => $urlFile,
            ];
        }

        return $result;
    }
}
