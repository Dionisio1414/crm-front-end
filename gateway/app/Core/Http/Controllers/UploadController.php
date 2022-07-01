<?php

namespace App\Core\Http\Controllers;

use App\Core\Model\Message;
use Illuminate\Http\Request;
use App\Users\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{

    const NON_IMAGE_FILE_PREVIEW_XML_PATH = 'app/non_image_file_preview';

    public function upload(Request $request)
    {
        try {
            if(!$request->input('file') || !$request->input('file') instanceof \Illuminate\Http\UploadedFile) {
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $path = 'public' . DIRECTORY_SEPARATOR . 'tmp';
                $file->storeAs($path, $fileName);
                return [
                    'success' => true,
                    'preview' => (strpos($file->getClientMimeType(), 'image/') === false)
                        ? \Settings::getConfigValueUrl(self::NON_IMAGE_FILE_PREVIEW_XML_PATH)
                        : Storage::url('tmp/' . $fileName),
                    'file'    => 'tmp/' . $fileName
                ];
            } else {
                throw new \Exception('Empty file value');
            }
        } catch (\Exception $e) {
            Message::addError($e->getMessage());
        }
        return ['success' => false];
    }

}