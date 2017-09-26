<?php
namespace App\Traits\Common;

use Illuminate\Support\Facades\Auth;
use App\Models\Media;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Exceptions\Api\UnknowException;
use Storage;

trait UploadableTrait
{
    public function uploadableTimestamp()
    {
        return 'Y/m';
    }

    public function uploadFile(UploadedFile $file, $path = 'other', $uploadDisk = 'image')
    {
        $storage = Storage::disk($uploadDisk);
        $target = $this->getTarget($path);
        $fileName = $this->getFileName($file);
        $destinationTarget = $target . '/' . $fileName;
        $count = 0;

        while ($storage->has($destinationTarget)) {
            $count++;
            $destinationTarget = $target . '/' . $count . '-' . $fileName;
        }

        $saved = $storage->put($destinationTarget, file_get_contents($file));

        if (!$saved) {
            return false;
        }

        return $destinationTarget;
    }

    public function destroyFile($destinationTarget, $uploadDisk = 'image')
    {
        $storage = Storage::disk($uploadDisk);

        if ($storage->has($destinationTarget)) {
            $storage->delete($destinationTarget);

            return true;
        }
    }

    public function getTarget($path)
    {
        if (method_exists($this, 'uploadableTimestamp') && is_string($this->uploadableTimestamp())) {
            $path = Carbon::now()->format($this->uploadableTimestamp()) . '/' . $path;
        }

        return $path;
    }

    public function getFileName(UploadedFile $file)
    {
        $fileName = $file->getClientOriginalName();
        $name = uniqid('img-' . time());
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);

        return $name . '.' . $ext;
    }

    public function makeDataMedias($data, $path = 'other')
    {
        if (!is_array($data)) {
            throw new UnknowException('Data type is incorrect');
        }

        $result = [];

        if (!empty($data['image'])) {
            foreach ($data['image'] as $value) {
                $result[] = [
                    'type' => Media::IMAGE,
                    'url_file' => $this->uploadFile($value, $path),
                ];
            }
        }

        if (!empty($data['video'])) {
            foreach ($data['video'] as $value) {
                $result[] = [
                    'type' => Media::VIDEO,
                    'url_file' => $value,
                ];
            }
        }

        return $result;
    }

    public function parseBase64($base64, $path = 'other', $uploadDisk = 'image')
    {
        $base64 = explode(';', $base64);
        $type = explode('/', $base64[0]);
        $image = explode(',', $base64[1]);
        $file = base64_decode($image[1]);
        $name = $this->getTarget($path) . '/img-' . md5(uniqid(rand(), true)) . '.' . $type[1];
        \Storage::disk($uploadDisk)->put($name, $file);

        return $name;
    }

    public function createDataMedias($data)
    {
        $result = [];

        foreach ($data as $value) {
            $result[] = [
                'type' => Media::IMAGE,
                'url_file' => $value,
            ];
        }

        return $result;
    }
}
