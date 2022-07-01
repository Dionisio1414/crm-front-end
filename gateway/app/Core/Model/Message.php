<?php
namespace App\Core\Model;

class Message
{

    public static function getMessages()
    {
        $messages = session()->pull('user_messages');
        return $messages ? json_decode($messages, true) : [];
    }

    public static function addError($message)
    {
        self::_addMessage('error', $message);
    }

    public static function addWarning($message)
    {
        self::_addMessage('warning', $message);
    }

    public static function addSuccess($message)
    {
        self::_addMessage('success', $message);
    }

    protected static function _addMessage($type, $message)
    {
        $messages = session()->get('user_messages');
        $messages = $messages ? json_decode($messages, true) : [];
        $messages[] = [
            'type' => $type,
            'text' => $message
        ];
        session()->put('user_messages', json_encode($messages));
    }

}