<?php

namespace App\Core\Traits;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

trait HasUploads
{

    public function getAttributeUrl($key)
    {
        $value = $this->getAttribute($key);
        return $value ? Request::root() . Storage::url(str_replace(DIRECTORY_SEPARATOR, '/', static::MEDIA_PATH) . $value) : false;
    }

    public function fill(array $attributes)
    {
        foreach($attributes as $key => $value) {
            if(!empty($this->mediaFields) && in_array($key, $this->mediaFields) && !empty($value['file']) && $value['file'] instanceof \Illuminate\Http\UploadedFile) {
                $value = $this->_uploadFile($value['file']);
                $attributes[$key] = $value;
            } elseif(strpos($value, 'tmp/') === 0) {
                $value = $this->_moveFromTmp($value);
                $attributes[$key] = $value;
            }
        }
        return parent::fill($attributes);
    }

    protected function _uploadFile($file)
    {
        $path = 'public' . DIRECTORY_SEPARATOR . static::MEDIA_PATH;
        $fileName = $file->getClientOriginalName();
        $path .= substr($fileName, 0, 1) . DIRECTORY_SEPARATOR . substr($fileName, 1, 1) . DIRECTORY_SEPARATOR;
        if(Storage::disk('local')->exists($path . $fileName)) {
            $iterator = 1;
            while(Storage::disk('local')->exists($path . $fileName)) {
                $fileName = str_replace(
                    '.' . $file->getClientOriginalExtension(),
                    $iterator++ . '.' . $file->getClientOriginalExtension(),
                    $file->getClientOriginalName()
                );
            }
        }

        $file->storeAs($path, $fileName);
        return substr($fileName, 0, 1) . '/' . substr($fileName, 1, 1) . '/' . $fileName;
    }

    protected function _moveFromTmp($file)
    {   
        $oldPath = 'public' . DIRECTORY_SEPARATOR;
        $newPath = 'public' . DIRECTORY_SEPARATOR . static::MEDIA_PATH;
        
        $fileName = explode('/', $file);
        $fileName = array_pop($fileName);
        $fileExtension = explode('.', $fileName);
        $fileExtension = array_pop($fileExtension);
        $newPath .= substr($fileName, 0, 1) . DIRECTORY_SEPARATOR . substr($fileName, 1, 1) . DIRECTORY_SEPARATOR;

        if(Storage::disk('local')->exists($newPath . $fileName)) {
            $iterator = 1;
            while(Storage::disk('local')->exists($newPath . $fileName)) {
                $fileName = str_replace(
                    '.' . $fileExtension,
                    $iterator++ . '.' . $fileExtension,
                    $fileName
                );
            }
        }
        Storage::move($oldPath . $file, $newPath . $fileName);
        return substr($fileName, 0, 1) . '/' . substr($fileName, 1, 1) . '/' . $fileName;
    }

    public function toArray()
    {
        $data = parent::toArray();
        foreach ($this->mediaFields as $key) {
            $data[$key . '_url'] = $this->getAttributeUrl($key);
        }
        return $data;

    }

}