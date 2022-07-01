<?php

namespace App\Services\Controllers\Chats;

use App\Core\Http\Controllers\Controller;
use App\Core\Traits\ApiResponder;
use App\Events\Chats\ReadMessage;
use App\Events\Chats\SendMessage;
use App\Events\Chats\StoreChat;
use App\Events\Chats\LikeMessage;
use App\Events\Chats\UpdateMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\Service\ChatService;


class ChatDataInController extends Controller
{
    use ApiResponder;

    //public $chatService;

//    public function __construct(ChatService $chatService)
//    {
//        $this->chatService = $chatService;
//    }

//    public function storeGroupChat(): JsonResponse
//    {
//        return $this->successResponse($this->chatService->storeGroupChat());
//    }

//    public function storePrivateChat(Request $request)
//    {
//        $data = $request->all();
//
//        foreach ($data['chat_users'] as $user) {
////            //broadcast(new \App\Events\PublicMessage('testing send public messages'));
//            if ($user['user_id'] != $data['chat']['message']['sender_id']) {
//                broadcast(new StoreGroupChat($user, $data))->toOthers();
//            }
//        }
//
//        return $data;
//    }
//
//    public function storeGroupChat(Request $request)
//    {
//        $data = $request->all();
//
//        foreach ($data['chat_users'] as $user) {
//            //broadcast(new \App\Events\PublicMessage('testing send public messages'));
//            if ($user['user_id'] != $data['chat']['message']['sender_id']) {
//                broadcast(new StoreGroupChat($user, $data))->toOthers();
//            }
//        }
//
//        return $data;
//    }

    public function storeChat(Request $request)
    {
        $data = $request->all();

        foreach ($data['chat_users'] as $user) {
            //broadcast(new \App\Events\PublicMessage('testing send public messages'));
            if ($user['user_id'] != $data['chat']['latest_message']['sender_id']) {
                broadcast(new StoreChat($user, $data))->toOthers();
            }
        }

        return $data;
    }

    public function sendMessage(Request $request)
    {
        $data = $request->all();

        foreach ($data['chat_users'] as $user) {
            //broadcast(new \App\Events\PublicMessage('testing send public messages'));
            if ($user['user_id'] != $data['chat']['latest_message']['sender_id']) {
                broadcast(new SendMessage($user, $data))->toOthers();
            }
        }

        return $data;
    }

    public function readMessages(Request $request)
    {
        $data = $request->all();

        foreach ($data as $value) {
            foreach ($value['users'] as $user) {
                broadcast(new ReadMessage($user, $value));
            }
            //broadcast(new \App\Events\PublicMessage('testing send public messages'));
//            if ($user['user_id'] != $data['chat']['latest_message']['sender_id']) {
//                broadcast(new SendMessage($user, $data))->toOthers();
//            }
        }

        return $data;
    }

    public function likeMessage(Request $request)
    {
        $data = $request->all();

        foreach ($data['message']['users'] as $user) {
            if ($user['user_id'] == $data['message']['sender_id']) {
                $data['notification'] = true;
                broadcast(new LikeMessage($user, $data));
            } else {
                broadcast(new LikeMessage($user, $data));
            }
        }

        return $data;
    }

    public function updateMessage(Request $request)
    {
        $data = $request->all();

        foreach ($data['users'] as $user) {
            broadcast(new UpdateMessage($user, $data));
        }

        return $data;
    }
}


