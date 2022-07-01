<?php

namespace App\Core\Http\Controllers;

use App\Core\Model\Message;
use App\Users\Controllers\Controller;

class MessagesController extends Controller
{
    public function list()
    {
        return Message::getMessages();
    }

}