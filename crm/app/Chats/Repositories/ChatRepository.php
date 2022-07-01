<?php

namespace App\Chats\Repositories;

use App\Core\Model\Yesno;
use App\Chats\Models\Chat as Model;
use App\Core\Repositories\CoreRepository;

/**
 * Class PropertyRepository.
 */
class ChatRepository extends CoreRepository
{
    /**
     * @return string
     *  Return the model
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function storeChat()
    {
        if (isset($this->request->is_group_chat) && $this->request->is_group_chat) {
            return $this->model()->create(array_merge($this->request->all(), ['author_id' => $this->request->user_id]));
        } else {
            return $this->model()->create($this->request->all());
        }
    }

    public function getStoreChat($id)
    {
        return $this->model()->with(['latestMessage'])->find($id);
    }

    public function getChatHistory($id)
    {
        return $this->model()->with([
            'messages' => function ($q) {
                $q->with([
                    'messagesStatus',
                ])->orderBy('created_at', 'desc');
            }
        ])->find($id);
    }

    public function getChatNotReads($id)
    {
        $user_id = $this->request->user_id;

        return $this->model()->with([
            'messages' => function ($q) use ($user_id) {
                $q->with([
                    'messagesStatus' => function ($q) use ($user_id) {
                        $q->where('user_id', $user_id)->where('is_read', Yesno::NO);
                    },
                ])->orderBy('created_at', 'desc');
            }
        ])->find($id);
    }

    public function getChats()
    {
        $user_id = $this->request->user_id;

        return $this->model()->whereHas('user', function ($q) use ($user_id) {
            $q->where('user_id', $user_id)->where('is_delete', Yesno::NO);
        })->with([
            'latestMessage' => function ($q) use ($user_id) {
                $q->with([
                    'messageStatus' => function ($q) use ($user_id) {
                        $q->where('user_id', '!=', $user_id);
                    },
                ]);
            },
            'user' => function ($q) use ($user_id) {
                $q->where('user_id', $user_id);
            },
        ])->withCount([
            'messages as messages_not_read_count' => function ($q) use ($user_id) {
                $q->whereHas(
                    'messageStatus', function ($q) use ($user_id) {
                    $q->where('user_id', $user_id)->where('is_read', Yesno::NO);
                }
                );
            }])->get()
            ->sortBy('user.is_fixed')->sortByDesc('latestMessage.created_at');
    }

    public function getChat($id)
    {
        return $this->model()->find($id);
    }

    public function updateChat($id)
    {
        $chat = $this->getChat($id);

        $this->model()->find($id)->update([
            'name'  => $this->request->name ?? $chat->name,
            'logo'  => $this->request->logo ?? $chat->logo,
        ]);
    }
}
