<?php

namespace App\Core\Helpers\FileManager;

class CheckFile
{
    public static $fileImageExtensions = [
        'jpg',
        'jpeg',
        'gif',
        'png',
    ];

    public static $max_upload = 5000;

    public static function checkBase64ImageFormat($base64_file, $type)
    {
        $base64_file = str_replace('data:image/' . $type . ';base64,', '', $base64_file);
        $base64_file = str_replace(' ', '+', $base64_file);
        $imgdata = base64_decode($base64_file);
        $f = finfo_open();
        $mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
        $mime_type = str_replace('image/', '', $mime_type);

        if (in_array($mime_type, self::$fileImageExtensions)) {
            return true;
        }

        return false;
    }

    public static function checkBase64ImageSize($base64_file){
        if(round((int)strlen(rtrim($base64_file, '=')) * 0.75 / 1024.4) <= self::$max_upload){
            return true;
        }

        return false;
    }

    /*  Validate Images */
    public static function validateImage($base64_file, $type)
    {
        if(!$base64_file || !$type){
            return [
                'message' => "Bad Request",
                'code' => \Illuminate\Http\Response::HTTP_BAD_REQUEST
            ];
        }

        if(!CheckFile::checkBase64ImageFormat($base64_file, $type)){
            return [
                'message' => __('validation.file_manager.type'),
                'code' => \Illuminate\Http\Response::HTTP_OK
            ];
        }

        if(!CheckFile::checkBase64ImageSize($base64_file)){
            return [
                'message' => __('validation.file_manager.size'),
                'code' => \Illuminate\Http\Response::HTTP_OK
            ];
        }

        return true;
    }
}
