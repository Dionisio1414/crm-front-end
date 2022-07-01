<?php

namespace App\Core\Traits;

use Illuminate\Support\Str;
use Notification;
use App\Notifications\Emails\EmailVerificationToken;
use App\Notifications\Emails\EmailVerificationPin;
use Daaner\TurboSMS\Facades\TurboSMS;

trait SenderService
{

    protected function sendVerificationEmail($email, $token = null)
    {
        if(!$token){
            $verification_token = Str::random(15);
        }else{
            $verification_token = $token;
        }

        $details = [
            'verification' => $verification_token,
            'email'=>$email
        ];

        $this->_sendNotification($email,$details);

        return $verification_token;
    }

    protected function sendToEmail($email, $data)
    {
        $details = [
            'data' => $data
        ];

        $this->_sendNotification($email,$details);

        return true;
    }

    protected function sendToEmailPin($email, $pin_code = null)
    {
        if(!$pin_code){
            $pin_code = mt_rand(1000, 9999);
        }

        $details = [
            'data' => $pin_code
        ];

        $this->_sendNotificationPin($email,$details);

        return $pin_code;
    }

    protected function sendVerificationPhone($phone)
    {
        $verification_pin = mt_rand(1000, 9999);

        TurboSMS::sendMessages(str_replace('+', '', $phone), 'Код для подтверждения телефона: ' . $verification_pin);

        return $verification_pin;
    }

    protected function _sendNotification($email,$data=[])
    {
        Notification::route('mail', $email)
            ->notify(new EmailVerificationToken($data));
    }

    protected function _sendNotificationPin($email, $data)
    {
        Notification::route('mail', $email)
            ->notify(new EmailVerificationPin($data));
    }
}
