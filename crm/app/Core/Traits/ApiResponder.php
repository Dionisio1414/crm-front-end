<?php


namespace App\Core\Traits;

use Illuminate\Http\Response;

trait ApiResponder
{
    public function successResponse($data, $code = Response::HTTP_OK) {
        return \response()->json(['data' => $data], $code);
    }
    public function errorResponse($message, $code) {
        return \response()->json([
            'status'=> 'errors',
            'message' => $message,
            'data' => null,
            'code'  => $code
        ], $code);
    }

    public function successMessage($message, $code = Response::HTTP_OK)
    {
        return \response()->json(['success' => $message], $code);
    }
}
