<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\DB;
use App\Traits\Common\UploadableTrait;
use App\Models\Media;

class CommonController extends ApiController
{
    use UploadableTrait;

    protected $dataSelect = [
        'image_default',
        'image_large',
        'image_medium',
        'image_slider',
        'image_small',
        'image_thumbnail',
    ];

    public function checkExist(Request $request)
    {
        $data = $request->only('column', 'database', 'value', 'idIgnore');

        return $this->getData(function () use ($data) {
            $query = DB::table($data['database'])->where($data['column'], $data['value']);

            if ($data['idIgnore']) {
                $query->whereNotIn('id', [$data['idIgnore']]);
            }

            $this->compacts['valid'] = $query->exists();
        });
    }

    public function uploadImageForEditor(Request $request)
    {
        $data = $request->only('file');
        $dataQuill = collect($data)->map(function ($file) {
            return [
                'type' => Media::EDITOR_QUILL,
                'url_file' => $this->getName($file),
            ];
        })->toArray();

        return $this->doAction(function () use ($dataQuill) {
            $this->compacts['media'] = $this->user
                ->media()
                ->createMany($dataQuill)
                ->map(function ($media) {
                    return $media->setVisible($this->dataSelect);
                });
        });
    }

    public function quillUploadedImages()
    {
        return $this->getData(function () {
            $this->compacts['images'] = $this->user->media()
                ->whereType(Media::EDITOR_QUILL)
                ->latest()
                ->get()
                ->map(function ($media) {
                    return $media->setVisible($this->dataSelect);
                });
        });
    }

    protected function getName($file)
    {
        $path = config('settings.folderQuill') . '/' . $this->user->id;

        return $this->uploadFile($file, $path);
    }

    public function getPageSlug($type, $id)
    {
        $repository = 'App\\Repositories\\Contracts\\' . studly_case($type) . 'Interface';

        return $this->getData(function () use ($repository, $id, $type) {
            if ($type == 'user') {
                $this->compacts['slug'] = app($repository)->findOrFail($id)->slug;
            } else {
                $this->compacts['slug'] = app($repository)->withTrashed()->findOrFail($id)->slug;
            }
        });
    }
}
