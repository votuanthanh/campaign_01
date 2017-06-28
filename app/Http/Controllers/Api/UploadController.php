<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Traits\Common\UploadableTrait;
use App\Exceptions\Api\NotFoundException;
use App\Exceptions\Api\UnknowException;

class UploadController extends ApiController
{
    use UploadableTrait;

    public function upload(Request $request)
    {
        return $this->uploadFile($request->file);
    }

    public function delete($path)
    {
        if (!$this->destroyFile($path)) {
            throw new NotFoundException('File not found!');
        }

        return $this->doAction(function () use ($path) {
            $this->compacts['delete'] = $path;
        });
    }
}
