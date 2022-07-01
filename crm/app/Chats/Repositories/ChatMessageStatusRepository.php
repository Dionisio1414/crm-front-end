<?php

namespace App\Chats\Repositories;

use App\Core\Model\Yesno;
use App\Chats\Models\ChatMessageStatus as Model;
use App\Core\Repositories\CoreRepository;

/**
 * Class ChatMessageStatusRepository.
 */
class ChatMessageStatusRepository extends CoreRepository
{
    /**
     * @return string
     *  Return the model
     */
    protected function getModelClass()
    {
        return Model::class;
    }

//    public function readMessages()
//    {
//        return $this->model()->whereIn('id', $this->request->data)->update([
//            'is_read' => Yesno::YES
//        ]);
//    }
//
//    public function getChatMessageStatus()
//    {
//        $user_id = $this->request->user_id;
//
//        return $this->model()->whereIn('id', $this->request->data)->with([
//            'message' => function ($q) use ($user_id) {
//                $q->with([
//                    'users' => function ($q) use ($user_id) {
//                        $q->where('user_id', '!=', $user_id);
//                    }
//                ]);
//            }
//        ])->get();
//    }
}
