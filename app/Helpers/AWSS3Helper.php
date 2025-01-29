<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class AWSS3Helper
{
    public function upload($file)
    {
        $fileName = 'profile/' . rand(1, 999999) . '-' . $file->getClientOriginalName();
        Storage::disk('s3')->put($fileName, file_get_contents($file));

        $url = Storage::disk('s3')->url($fileName);

        return [
            'path' => $fileName,
            'url'  => $url
        ];
    }
}
