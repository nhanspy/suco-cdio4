<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StorageService
{
    private $avatar;

    public function __construct()
    {
        $this->avatar = config('filesystems.avatar');
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function storeAvatar($file)
    {
        $disk = $this->avatar['disk'];

        $path = $file->store($this->avatar['save_path'], $disk);

        if ($disk === 'local') {
            $url = config('filesystems.disks.'.$disk.'.root');
        } else {
            $url = config('filesystems.disks.'.$disk.'.url');
        }

        return $url.'/'.$path;
    }

    /**
     * @param $avatarUrl
     * @return boolean
     */
    public function deleteAvatar($avatarUrl)
    {
        $ar = explode('/', $avatarUrl);
        $hashName = end($ar);

        return Storage::disk($this->avatar['disk'])->delete($this->avatar['save_path'].'/'.$hashName);
    }
}
