<?php

namespace App\Chats\Repositories;

use App\Chats\Models\ChatMessage;
use App\Core\Helpers\UsefulHelper;
use App\Core\Model\Yesno;
use App\Chats\Models\ChatMessage as Model;
use App\Core\Repositories\CoreRepository;

/**
 * Class PropertyRepository.
 */
class ChatMessageRepository extends CoreRepository
{
    /**
     * @return string
     *  Return the model
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function firstMessage($id)
    {
        return $this->model()->find($id);
    }

    public function createMessage($chat_id, $users, $text = null, $params = null)
    {
        $create_message = $this->model()->create([
            'sender_id' => $this->request->user_id,
            'chat_id' => $chat_id,
            'text' => $text,
            'params' => $params
        ]);

        if (isset($users)) {
            foreach ($users as $user) {
                if ($user['user_id'] != $this->request->user_id) {
                    $create_message->messagesStatus()->create([
                        'user_id' => $user['user_id']
                    ]);
                }
            }
        }
        return $create_message;
        //$create_message->messagesStatus()->saveMany($this->request->users);
    }

    public function getChatHistory($id)
    {
        $user_id = $this->request->user_id;

        return $this->model()->where(function ($q) use ($user_id, $id) {
            $q->where('chat_id', $id)->whereHas(
                'messagesStatus', function ($qms) use ($user_id) {
                $qms->where('user_id', $user_id)->where('is_read', Yesno::YES);
            });
        })->orWhere(function ($q) use ($user_id, $id) {
            $q->where('chat_id', $id)->where('sender_id', $user_id);
        })->with(['messagesStatus'])->orderBy('created_at', 'desc')->paginate($this->request->paginate);
    }

    public function getChatNotReads($id)
    {
        $user_id = $this->request->user_id;

        return $this->model()->where('chat_id', $id)->whereHas(
            'messagesStatus', function ($q) use ($user_id) {
            $q->where('user_id', $user_id)->where('is_read', Yesno::NO);
        })->with(['messagesStatus'])->orderBy('created_at', 'desc')->get();
    }

    public function readMessages()
    {
        $user_id = $this->request->user_id;

        $messages = $this->model()->whereIn('id', $this->request->data)->whereHas(
            'messagesStatus', function ($q) use ($user_id) {
            $q->where('user_id', $user_id);
        })->get();

        foreach ($messages as $message) {
            $message->messagesStatus()->update([
                'is_read' => Yesno::YES
            ]);
        }
    }

    public function getMessages()
    {
        $user_id = $this->request->user_id;

        return $this->model()->whereIn('id', $this->request->data)->with([
            'users' => function ($q) use ($user_id) {
                $q->where('user_id', '!=', $user_id);
            },
            'messageStatus' => function ($q) use ($user_id) {
                $q->where('user_id', $user_id);
            }
        ])->get();
    }

    public function likeMessage($id)
    {
        $user_id = $this->request->user_id;

        $message = $this->firstMessage($id)->toArray();

        if (isset($message['params']['likes']) && in_array($user_id, $message['params']['likes'])) {
            $message['params']['likes'] = array_diff($message['params']['likes'], [$user_id]);
            $is_like = false;
        } else {
            if(!isset($message['params']['likes'])){
                $message['params']['likes'] = [];
            }
            $message['params']['likes'] = array_merge($message['params']['likes'], [$user_id]);
            $is_like = true;
        }

        $this->firstMessage($id)->update([
            'params' => $message['params']
        ]);

        $items = [
            'id' => $user_id,
            'is_like' => $is_like
        ];

        return $items;
    }

    public function messageUsers($id)
    {
        $user_id = $this->request->user_id;

        return $this->model()->with([
            'users' => function ($q) use ($user_id) {
                $q->where('user_id', '!=', $user_id);
            },
            'messageStatus' => function ($q) use ($user_id) {
                $q->where('user_id', $user_id);
            }
        ])->find($id);
    }

    public function updateMessage($id)
    {
        $item = $this->firstMessage($id)->toArray();
        $user_id = $this->request->user_id;
        if ($this->request->message) {
            $item['params']['is_edit'] = Yesno::YES;

            $this->firstMessage($id)->update([
                'text' => $this->request->message,
                'params' => $item['params']
            ]);
            $action = Model::EDIT;
        } elseif (isset($this->request->is_delete) && $this->request->is_delete) {
            $item['params']['is_delete'] = Yesno::YES;

            if (isset($item['params']['files_id'])) {
                unset($item['params']['files_id']);
            }

            if (isset($item['params']['media_id'])) {
                unset($item['params']['media_id']);
            }

            if (isset($item['params']['links'])) {
                unset($item['params']['links']);
            }

            $this->firstMessage($id)->update([
                'text' => null,
                'params' => $item['params']
            ]);
            $action = Model::DELETE;
        } elseif (isset($this->request->is_read)) {
            $message = $this->model()->where('id', $id)->whereHas(
                'messageStatus', function ($q) use ($user_id) {
                $q->where('user_id', $user_id);
            })->with('messageStatus')->first();

            $message->messageStatus()->update([
                'is_read' => $this->request->is_read
            ]);
            $action = Model::READ;
        }

        $message_user = $this->messageUsers($id);
        $message_user->action = $action;
        return $message_user;
    }

    public function getContentChat($id)
    {
        return $this->model()->select('id' ,'params')->where('chat_id' ,$id)->whereNotNull('params->'. $this->request->params)->orderBy('created_at', 'desc')->get();
    }

    public function getContentUser($id)
    {
        return $this->model()->select('id' ,'params')->where('sender_id' ,$id)->whereNotNull('params->'. $this->request->params)->orderBy('created_at', 'desc')->get();
    }

//    public function createPrivateMessage($chat_id, $users, $text = null)
//    {
//        $create_message = $this->model()->create([
//            'sender_id' => $this->request->user_id,
//            'chat_id' => $chat_id,
//            'text' => $text
//        ]);
//
//        if (isset($users)) {
//            foreach ($users as $user) {
//                if ($user['user_id'] != $this->request->user_id) {
//                    $create_message->messagesStatus()->create([
//                        'user_id' => $user['user_id']
//                    ]);
//                }
//            }
//        }
//        return $create_message;
//        //$create_message->messagesStatus()->saveMany($this->request->users);
//    }
}
