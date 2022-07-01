<?php

namespace App\Services\Service;

use App\Core\Traits\ApiResponder;
use App\Core\Traits\ConsumeExternalService;
use App\Core\Traits\CoreServiceCrm;

class ChatService
{
    use ConsumeExternalService, CoreServiceCrm, ApiResponder;

    public function storePrivateChat()
    {


        return $this->performRequestForm('POST', 'api/v1/chats/store-private-chat', $this->request);
    }

    public function storeGroupChat()
    {
        return $this->performRequestForm('POST', 'api/v1/chats/store-group-chat', $this->request);
    }

    public function sendMessage()
    {
        return $this->performRequestForm('POST', 'api/v1/chats/send-message', $this->request);
    }

    public function updateMessage($id)
    {
        return $this->performRequestForm('PUT', 'api/v1/chats/update-message/'. $id, $this->request);
    }

    public function getChats()
    {
        return $this->performRequestQuery('GET', 'api/v1/chats/get-chats', $this->request);
    }

    public function getChat($id)
    {
        return $this->performRequestQuery('GET', 'api/v1/chats/get-chat/'. $id, $this->request);
    }

    public function readMessages()
    {
        return $this->performRequestForm('POST', 'api/v1/chats/read-messages', $this->request);
    }

    public function likeMessage($id)
    {
        return $this->performRequestForm('PUT', 'api/v1/chats/like-message/'. $id, $this->request);
    }

    public function updateChatOptions($id)
    {
        return $this->performRequestForm('PUT', 'api/v1/chats/update-chat-options/'. $id, $this->request);
    }

    public function updateChat($id)
    {
        return $this->performRequestForm('PUT', 'api/v1/chats/update-chat/'. $id, $this->request);
    }

    public function addUsersToChat($id)
    {
        return $this->performRequestForm('POST', 'api/v1/chats/add-users-to-chat/'. $id, $this->request);
    }

    public function getContentChat($id)
    {
        return $this->performRequestQuery('GET', 'api/v1/chats/get-content-chat/'. $id, $this->request);
    }

    public function getContentUser($id)
    {
        return $this->performRequestQuery('GET', 'api/v1/chats/get-content-user/'. $id, $this->request);
    }

//    public function searchChats()
//    {
//        return $this->performRequestForm('POST', 'api/v1/chats/search-chats', $this->request);
//    }
}
