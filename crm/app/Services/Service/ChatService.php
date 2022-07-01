<?php

namespace App\Services\Service;

use App\Core\Traits\ConsumeExternalService;
use App\Core\Traits\CoreService;

class ChatService
{
    use ConsumeExternalService, CoreService;

    public function testChat($items)
    {
        return $this->performRequestQuery('GET', 'api/v1/chat/test', $items);
    }

//    public function storePrivateChat($items)
//    {
//        return $this->performRequestQuery('GET', 'api/v1/chat-data-in/store-private-chat', $items);
//    }
//
//    public function storeGroupChat($items)
//    {
//        return $this->performRequestQuery('GET', 'api/v1/chat-data-in/store-group-chat', $items);
//    }

    public function storeChat($items)
    {
        return $this->performRequestQuery('GET', 'api/v1/chat-data-in/store-chat', $items);
    }

    public function sendMessage($items)
    {
        return $this->performRequestQuery('GET', 'api/v1/chat-data-in/send-message', $items);
    }

    public function readMessages($items)
    {
        return $this->performRequestQuery('GET', 'api/v1/chat-data-in/read-messages', $items);
    }

    public function likeMessage($items)
    {
        return $this->performRequestQuery('GET', 'api/v1/chat-data-in/like-message', $items);
    }

    public function updateMessage($items)
    {
        return $this->performRequestQuery('GET', 'api/v1/chat-data-in/update-message', $items);
    }
}
