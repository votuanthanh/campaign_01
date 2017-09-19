<?php

namespace App\Repositories\Eloquent;

use App\Models\Media;
use Storage;
use App\Traits\Common\UploadableTrait;
use App\Repositories\Contracts\MediaInterface;

class MediaRepository extends BaseRepository implements MediaInterface
{
    use UploadableTrait;

    public function model()
    {
        return Media::class;
    }

    public function deleteMedia($media)
    {
        if ($media->isEmpty()) {
            return false;
        }

        $media->map(function ($item, $key) {
            $item->forceDelete();

            if ($item->type == Media::IMAGE) {
                return $this->destroyFile($item->url_file);
            }

            return true;
        });

        return true;
    }
}
