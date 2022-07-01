<?php

namespace App\Core\Traits;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\CommonMark\Inline\Element\Image;

trait UploadImage
{
    public function uploadImageBase64($company_id, $image, $type, $title = null, $path = null)
    {
        $path =  $company_id . '/' . $path;
        $image = str_replace('data:image/' . $type . ';base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $title = $title ?? Str::random(10) . '.' . $type;
        $path .= $title;

        $image = base64_decode($image);

        if($is_upload = $this->_uploadFile($path, $image)){
            return [
                'path'      => $path,
                'full_path' => $this->_getUrlFile($path),
                'title'     => $title,
                'is_image'  => true
            ];
        }

        return false;
    }

    public function _uploadFile($filePath, $file)
    {
        return Storage::disk('s3')->put($filePath, $file);
    }
    public function _getUrlFile($filePath)
    {
        return Storage::disk('s3')->url($filePath);
    }

    public function removeFiles($files)
    {
        foreach ($files as $file){
            Storage::disk('s3')->delete($file->path);
        }
    }

    public function removeFile($file)
    {
        return Storage::disk('s3')->delete($file->path);
    }
}
