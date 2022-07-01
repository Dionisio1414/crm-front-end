<?php

namespace App\Chats\Repositories;

use App\Core\Model\Yesno;
use App\Chats\Models\ChatUser as Model;
use App\Core\Repositories\CoreRepository;

/**
 * Class PropertyRepository.
 */
class ChatUserRepository extends CoreRepository
{
    /**
     * @return string
     *  Return the model
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function firstOrCreate($chat_id, $user_id)
    {
       return  $this->model()->firstOrCreate([
            'chat_id' => $chat_id,
            'user_id' => $user_id
        ]);
    }

    public function firstOrCreateGroupChatUser($chat_id)
    {
        $this->firstOrCreate($chat_id, $this->request->user_id);
        if (isset($this->request->users)) {
            foreach ($this->request->users as $user) {
                $this->firstOrCreate($chat_id, $user['id']);
            }
        }
    }

    public function firstOrCreatePrivateChatUser($chat_id)
    {
        $this->firstOrCreate($chat_id, $this->request->user_id);
        $this->firstOrCreate($chat_id, $this->request->recipient_id);
    }

    public function getChatUser($chat_id)
    {
        return $this->model()->where('chat_id', $chat_id)->whereNotNull('user_id')->get();
    }

    public function updateChatOptions($chat_id)
    {
        $item = $this->model()->where('chat_id', $chat_id)
            ->where('user_id', $this->request->user_id)->first();

        return $item->update([
            'is_delete' => $this->request->is_delete ?? $item->is_delete,
            'is_notification' => $this->request->is_notification ?? $item->is_notification,
            'is_fixed' => $this->request->is_fixed ?? $item->is_fixed
        ]);
    }
}
