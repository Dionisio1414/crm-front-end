<?php

namespace App\Chats\Controllers;

use App\Chats\Models\Chat;
use App\Chats\Repositories\ChatMessageRepository;
use App\Chats\Repositories\ChatMessageStatusRepository;
use App\Chats\Repositories\ChatRepository;
use App\Chats\Repositories\ChatUserRepository;
use App\Core\Traits\ApiResponder;
use App\Services\Service\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;
use Illuminate\Support\Facades\Validator;
use App\Core\Helpers\PaginateHelper;
use App\Core\Helpers\UsefulHelper;


class ChatController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $chatRepository;
    private $chatUserRepository;
    private $chatMessageRepository;
    private $chatMessageStatusRepository;
    private $chatService;

    public function __construct(ChatRepository $chatRepository, ChatUserRepository $chatUserRepository, ChatMessageRepository $chatMessageRepository,
                                ChatMessageStatusRepository $chatMessageStatusRepository, ChatService $chatService)
    {
        /* Set database */
        $db = request('db');
        DatabaseConnection::setConnection([
            'db_database' => $db
        ]);

        //Repository
        $this->chatRepository = $chatRepository;
        $this->chatUserRepository = $chatUserRepository;
        $this->chatMessageRepository = $chatMessageRepository;
        $this->chatMessageStatusRepository = $chatMessageStatusRepository;
        $this->chatService = $chatService;
    }

    public function storePrivateChat(Request $request): JsonResponse
    {
        $chat_create = $this->chatRepository->storeChat();
        $this->chatUserRepository->firstOrCreatePrivateChatUser($chat_create->id);
        $chat_users = $this->chatUserRepository->getChatUser($chat_create->id);

        $this->chatMessageRepository->createMessage($chat_create->id, $chat_users, $request->message, $request->params);
        $chat = $this->chatRepository->getStoreChat($chat_create->id);
        $chat->action = Chat::CREATE_PRIVATE_CHAT;

        $items = [
            'chat' => $chat->toArray(),
            'chat_users' => $chat_users->toArray()
        ];
        $this->chatService->storeChat($items);
        //$this->chatService->storePrivateChat($items);

        return $this->successResponse($items);
    }

    public function storeGroupChat(Request $request)
    {
        $chat_create = $this->chatRepository->storeChat();
        $this->chatUserRepository->firstOrCreateGroupChatUser($chat_create->id);
        $chat_users = $this->chatUserRepository->getChatUser($chat_create->id);

        $this->chatMessageRepository->createMessage($chat_create->id, $chat_users, 'New group chat created: ' . $chat_create->name);
        $chat = $this->chatRepository->getStoreChat($chat_create->id);
        $chat->action = Chat::CREATE_GROUP_CHAT;

        $items = [
            'chat' => $chat->toArray(),
            'chat_users' => $chat_users->toArray()
        ];

        $this->chatService->storeChat($items);
        //$this->chatService->storeGroupChat($items);
        return $this->successResponse($items);
    }

    public function sendMessage(Request $request)
    {
        $chat_users = $this->chatUserRepository->getChatUser($request->chat_id);
        $this->chatMessageRepository->createMessage($request->chat_id, $chat_users, $request->message, $request->params);
        $chat = $this->chatRepository->getStoreChat($request->chat_id);

        $items = [
            'chat' => $chat->toArray(),
            'chat_users' => $chat_users->toArray()
        ];

        $this->chatService->sendMessage($items);

        return $this->successResponse($items);
    }

    public function getChats(Request $request)
    {
        $chats = $this->chatRepository->getChats();
        $chats_paginate = PaginateHelper::paginate(collect($chats), $request->paginate);
        $chats_array = UsefulHelper::iterateOverArray($chats_paginate->items());

        $items = [
            'body' => $chats_array,
            'total_page' => $chats_paginate->lastPage(),
            'total' => $chats_paginate->total()
        ];
        return $this->successResponse($items);
    }

    public function getChat(Request $request, $id)
    {
        $chat_history = $this->chatMessageRepository->getChatHistory($id);
        $chat_not_reads = null;

        if (isset($request->load_last_message)) {
            $chat_not_reads = $this->chatMessageRepository->getChatNotReads($id);
        }

        $items = [
            'body_history' => $chat_history,
            'body_not_reads' => $chat_not_reads
        ];

        return $this->successResponse($items);
    }

    public function readMessages(Request $request)
    {
        //$this->chatMessageStatusRepository->readMessages();
        $this->chatMessageRepository->readMessages();
        $messages = $this->chatMessageRepository->getMessages();
        //$chat_message_status = $this->chatMessageStatusRepository->getChatMessageStatus();
        $this->chatService->readMessages($messages->toArray());

        return $this->successResponse($messages);
    }

    public function likeMessage(Request $request, $id)
    {
        $is_like = $this->chatMessageRepository->likeMessage($id);
        $message = $this->chatMessageRepository->messageUsers($id);

        $items = [
            'is_like' => $is_like,
            'message' => $message->toArray()
        ];

        $this->chatService->likeMessage($items);
        return $this->successResponse($items);
    }

    public function updateChatOptions(Request $request, $id)
    {
        $this->chatUserRepository->updateChatOptions($id);
        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

    public function updateMessage(Request $request, $id)
    {
        $message = $this->chatMessageRepository->updateMessage($id);
        $this->chatService->updateMessage($message->toArray());

        return $this->successResponse($message);
    }

    public function updateChat(Request $request, $id)
    {
        $this->chatRepository->updateChat($id);
        $chat_users = $this->chatUserRepository->getChatUser($id);
        $chat = $this->chatRepository->getStoreChat($id);
        $chat->action = Chat::UPDATE_CHAT;

        $items = [
            'chat' => $chat->toArray(),
            'chat_users' => $chat_users->toArray()
        ];

        $this->chatService->storeChat($items);

        return $this->successResponse($items);
    }

    public function addUsersToChat(Request $request, $id)
    {
        foreach ($request->users as $user) {
            $this->chatUserRepository->firstOrCreate($id, $user['id']);
        }

        $chat_users = $this->chatUserRepository->getChatUser($id);

        $this->chatMessageRepository->createMessage($id, $chat_users, 'New users in chats: ');
        $chat = $this->chatRepository->getStoreChat($id);
        $chat->latestMessage->added_users_to_chat = $request->users;
        $chat->action = Chat::ADDED_USERS_TO_CHAT;

        $items = [
            'chat' => $chat->toArray(),
            'chat_users' => $chat_users->toArray()
        ];

        $this->chatService->sendMessage($items);

        return $this->successResponse($items);
    }

    public function getContentChat(Request $request, $id)
    {
        $chat = $this->chatMessageRepository->getContentChat($id);
        return $this->successResponse($chat);
    }

    public function getContentUser(Request $request, $id)
    {
        $chat = $this->chatMessageRepository->getContentUser($id);
        return $this->successResponse($chat);
    }

//    public function searchChats()
//    {
//       // $chats =
//        return $this->successResponse();
//    }
}
