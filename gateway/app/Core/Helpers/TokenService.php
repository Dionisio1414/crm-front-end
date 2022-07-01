<?php

namespace App\Core\Helpers;

use Carbon\Carbon;
use Browser;

class TokenService
{
    public static function _create($user)
    {
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        $token->expires_at = Carbon::now()->addDays($user->session_life_day);
        $token->save();

        return $tokenResult;
    }

    public static function getUserAgent($request)
    {
        $data = [
            'ip' => $request->getClientIp(),
            'user_agent' => Browser::browserName()
        ];
        return $data;
    }
}
