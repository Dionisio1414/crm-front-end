<?php

namespace App\Core\Helpers;

class CheckFile
{
    public static function check($path)
    {
        $fileExtensions = [
            'jpg',
            'jpeg',
            'gif',
            'png',
            'bmp',
            'svg',
            'svgz',
            'cgm',
            'djv',
            'djvu',
            'ico',
            'ief',
            'jpe',
            'pbm',
            'pgm',
            'pnm',
            'ppm',
            'ras',
            'rgb',
            'tif',
            'tiff',
            'wbmp',
            'xbm',
            'xpm',
            'xwd'
        ];

        $explodeImage = explode('.', $path);
        $extension = end($explodeImage);

        if (in_array($extension, $fileExtensions)) {
            return true;
        } else {
            return false;
        }
    }
}
