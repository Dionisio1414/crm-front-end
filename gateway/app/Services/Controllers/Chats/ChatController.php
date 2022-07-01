<?php

namespace App\Services\Controllers\Chats;

use App\Core\Http\Controllers\Controller;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\Service\ChatService;

class ChatController extends Controller
{
    use ApiResponder;

    public $chatService;

    /**
     * Create a new controller instance.
     *
     * @param ChatService $chatService
     */
    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * @OA\Post(
     *      path="/chats/store-private-chat",
     *      tags={"Chats"},
     *      summary="Store Private Chat",
     *      @OA\RequestBody(
     *          description="Change recepient_id, message, params(files_id[id], forward_id(forwarded message), media_id[id], links[https://link.ua])",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    property="recepient_id",
     *                    title="ID",
     *                    example=1,
     *                    type="int"
     *               ),
     *              @OA\Property(
     *                    property="message",
     *                    title="Message",
     *                    example="Hello World",
     *                    type="string"
     *               ),
     *               @OA\Property(
     *                    property="params",
     *                       @OA\Property(
     *                           property="files_id",
     *                           title="files_id",
     *                           example={1,2},
     *                       ),
     *                      @OA\Property(
     *                           property="media_id",
     *                           title="files_id",
     *                           example={1,2},
     *                       ),
     *                      @OA\Property(
     *                           property="links",
     *                           title="files_id",
     *                           example={"https://link.ua","https://link2.ua"},
     *                       ),
     *                      @OA\Property(
     *                          property="forward_id",
     *                          title="forward_id",
     *                          type="integer",
     *                          example=1,
     *                       ),
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Table Data Suppliers",
     *                 @OA\Property(
     *                    property="chat",
     *                    title="Get Chat",
     *                     ref="#/components/schemas/Chat"
     *                  ),
     *                  @OA\Property(
     *                    property="chat_users",
     *                    title="Get Chat Users",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/ChatUser"
     *                    )
     *                  ),
     *              ),
     *              @OA\Property(
     *                  property="socket_event",
     *                  example=".private.store_chat"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function storePrivateChat(): JsonResponse
    {
        return $this->successResponse($this->chatService->storePrivateChat());
    }

    /**
     * @OA\Post(
     *      path="/chats/store-group-chat",
     *      tags={"Chats"},
     *      summary="Store Group Chat",
     *      @OA\RequestBody(
     *          description="Change recepient_id, message, params(files_id[id], forward_id(forwarded message), media_id[id], links[https://link.ua])",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    property="is_group_chat",
     *                    title="is group chat",
     *                    example=true,
     *                    type="boolean"
     *               ),
     *              @OA\Property(
     *                    property="name",
     *                    title="Name",
     *                    example="Name",
     *                    type="string"
     *               ),
     *               @OA\Property(
     *                    property="logo",
     *                    title="Logo",
     *                    example="logo.png",
     *                    type="string"
     *               ),
     *               @OA\Property(
     *                    property="users",
     *                    type="array",
     *                    collectionFormat="multi",
     *                        @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              title="Id",
     *                              example=1,
     *                              type="int"
     *                          ),
     *                        )
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Table Data Suppliers",
     *                 @OA\Property(
     *                    property="chat",
     *                    title="Get Chat",
     *                     ref="#/components/schemas/Chat"
     *                  ),
     *                  @OA\Property(
     *                    property="chat_users",
     *                    title="Get Chat Users",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/ChatUser"
     *                    )
     *                  ),
     *              ),
     *              @OA\Property(
     *                  property="socket_event",
     *                  example=".private.store_chat"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function storeGroupChat(): JsonResponse
    {
        return $this->successResponse($this->chatService->storeGroupChat());
    }

    /**
     * @OA\Post(
     *      path="/chats/send-message",
     *      tags={"Chats"},
     *      summary="Send Message",
     *      @OA\RequestBody(
     *          description="Change recepient_id, message, params(files_id[id], forward_id(forwarded message), media_id[id], links[https://link.ua], reply_id(reply to message))",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    property="chat_id",
     *                    title="ID",
     *                    example=1,
     *                    type="int"
     *               ),
     *              @OA\Property(
     *                    property="message",
     *                    title="Message",
     *                    example="Hello World",
     *                    type="string"
     *               ),
     *               @OA\Property(
     *                    property="params",
     *                       @OA\Property(
     *                           property="files_id",
     *                           title="files_id",
     *                           example={1,2},
     *                       ),
     *                      @OA\Property(
     *                           property="media_id",
     *                           title="files_id",
     *                           example={1,2},
     *                       ),
     *                      @OA\Property(
     *                           property="links",
     *                           title="files_id",
     *                           example={"https://link.ua","https://link2.ua"},
     *                       ),
     *                      @OA\Property(
     *                          property="forward_id",
     *                          title="forward_id",
     *                          type="integer",
     *                          example=1,
     *                       ),
     *                      @OA\Property(
     *                          property="reply_id",
     *                          title="reply_id",
     *                          type="integer",
     *                          example=1,
     *                       ),
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Table Data Suppliers",
     *                 @OA\Property(
     *                    property="chat",
     *                    title="Get Chat",
     *                     ref="#/components/schemas/Chat"
     *                  ),
     *                  @OA\Property(
     *                    property="chat_users",
     *                    title="Get Chat Users",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/ChatUser"
     *                    )
     *                  ),
     *              ),
     *              @OA\Property(
     *                  property="socket_event",
     *                  example=".private.send_message"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function sendMessage(): JsonResponse
    {
        return $this->successResponse($this->chatService->sendMessage());
    }

    /**
     * @OA\Put(
     *      path="/chats/update-message/{id}",
     *      tags={"Chats"},
     *      summary="Update Message",
     *      @OA\RequestBody(
     *          description="Change message, params(is_edit(true||false),is_delete(true||false),is_read(true||false))",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    property="message",
     *                    title="Message",
     *                    example="Hello World",
     *                    type="string"
     *               ),
     *               @OA\Property(
     *                    property="params",
     *                       @OA\Property(
     *                           property="is_edit",
     *                           title="is_edit",
     *                           example=true,
     *                           type="boolean"
     *                       ),
     *                       @OA\Property(
     *                           property="is_delete",
     *                           title="is_delete",
     *                           example=true,
     *                           type="boolean"
     *                       ),
     *                       @OA\Property(
     *                           property="is_read",
     *                           title="is_read",
     *                           example=true,
     *                           type="boolean"
     *                       ),
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Table Data Suppliers",
     *                @OA\Property(
     *                    property="id",
     *                    title="ID",
     *                    example=1,
     *                    type="int"
     *               ),
     *               @OA\Property(
     *                    property="sender_id",
     *                    title="Sender ID",
     *                    example=1,
     *                    type="int"
     *               ),
     *               @OA\Property(
     *                    property="chat_id",
     *                    title="Chat ID",
     *                    example=1,
     *                    type="int"
     *               ),
     *                 @OA\Property(
     *                    property="text",
     *                    title="Text",
     *                    example="Hello World",
     *                    type="string"
     *               ),
     *               @OA\Property(
     *                    property="params",
     *                       @OA\Property(
     *                           property="is_edit",
     *                           title="is_edit",
     *                           example=true,
     *                           type="boolean"
     *                       ),
     *                       @OA\Property(
     *                           property="is_delete",
     *                           title="is_delete",
     *                           example=true,
     *                           type="boolean"
     *                       ),
     *                       @OA\Property(
     *                           property="is_read",
     *                           title="is_read",
     *                           example=true,
     *                           type="boolean"
     *                       ),
     *                      ),
     *                    @OA\Property(
     *                      property="created_at",
     *                      title="Created",
     *                      example="2020-09-18 11:42:35",
     *                      type="timestamp"
     *                    ),
     *                  @OA\Property(
     *                      property="updated_at",
     *                      title="Updated",
     *                      example="2020-09-18 11:42:35",
     *                      type="timestamp"
     *                  ),
     *                  @OA\Property(
     *                    property="action",
     *                    title="edit",
     *                    example="edit",
     *                    type="string"
     *                 ),
     *                  @OA\Property(
     *                    property="users",
     *                    title="Get Users",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/ChatUser"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="message_status",
     *                    title="Get Message status",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/ChatMessageStatus"
     *                    )
     *                  ),
     *              ),
     *              @OA\Property(
     *                  property="socket_event",
     *                  example=".private.update_message"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function updateMessage($domain, $id): JsonResponse
    {
        return $this->successResponse($this->chatService->updateMessage($id));
    }

    /**
     * @OA\Get(
     *      path="/chats/get-chats",
     *      tags={"Chats"},
     *      summary="Get Chats",
     *      @OA\Parameter(
     *         name="page",
     *         description="Page Number",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *       ),
     *       @OA\Parameter(
     *           name="s",
     *           description="Search",
     *           required=false,
     *           in="query",
     *           @OA\Schema(
     *                type="string"
     *           )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Documents in Warehouse",
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *               @OA\Property(
     *                    property="id",
     *                    title="ID",
     *                    example=1,
     *                    type="int"
     *               ),
     *                 @OA\Property(
     *                    property="name",
     *                    title="Text",
     *                    example="test chat",
     *                    type="string"
     *                  ),
     *                  @OA\Property(
     *                    property="logo",
     *                    title="Text",
     *                    example="logo.png",
     *                    type="string"
     *                  ),
     *                 @OA\Property(
     *                    property="is_group_chat",
     *                    title="Text",
     *                    example=true,
     *                    type="boolean"
     *                  ),
     *                  @OA\Property(
     *                    property="author_id",
     *                    title="author id",
     *                    example=1,
     *                    type="int"
     *                  ),
     *                  @OA\Property(
     *                      property="created_at",
     *                      title="Created",
     *                      example="2020-09-18 11:42:35",
     *                      type="timestamp"
     *                   ),
     *                  @OA\Property(
     *                      property="updated_at",
     *                      title="Updated",
     *                      example="2020-09-18 11:42:35",
     *                      type="timestamp"
     *                  ),
     *                  @OA\Property(
     *                    property="messages_not_read_count",
     *                    title="int",
     *                    example="10",
     *                    type="int"
     *                 ),
     *                 @OA\Property(
     *                    property="latest_message",
     *                    title="Latest Message",
     *                    ref="#/components/schemas/ChatMessage"
     *                  ),
     *                  @OA\Property(
     *                    property="user",
     *                    title="Get Users",
     *                    ref="#/components/schemas/ChatUser"
     *                  ),
     *                  ),
     *                  @OA\Property(
     *                    property="total",
     *                    title="Total Data",
     *                    type="integer",
     *                  ),
     *                  @OA\Property(
     *                    property="total_page",
     *                    title="Total Page",
     *                    type="integer",
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function getChats(): JsonResponse
    {
        return $this->successResponse($this->chatService->getChats());
    }

    /**
     * @OA\Get(
     *      path="/chats/get-chat/{id}",
     *      tags={"Chats"},
     *      summary="Get Chats",
     *      @OA\Parameter(
     *         name="page",
     *         description="Page Number",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *       ),
     *       @OA\Parameter(
     *           name="s",
     *           description="Search",
     *           required=false,
     *           in="query",
     *           @OA\Schema(
     *                type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="load_last_message",
     *           description="Search",
     *           required=false,
     *           in="query",
     *           @OA\Schema(
     *                type="boolean"
     *           )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Documents in Warehouse",
     *                @OA\Property(
     *                    property="body_history",
     *                    title="Get Table Data Body History",
     *                  @OA\Property(
     *                    property="data",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                      @OA\Property(
     *                    property="id",
     *                    title="ID",
     *                    example=1,
     *                    type="int"
     *                     ),
     *               @OA\Property(
     *                    property="sender_id",
     *                    title="Sender ID",
     *                    example=1,
     *                    type="int"
     *               ),
     *               @OA\Property(
     *                    property="chat_id",
     *                    title="Chat ID",
     *                    example=1,
     *                    type="int"
     *               ),
     *                 @OA\Property(
     *                    property="text",
     *                    title="Text",
     *                    example="Hello World",
     *                    type="string"
     *               ),
     *               @OA\Property(
     *                    property="params",
     *                       @OA\Property(
     *                           property="is_edit",
     *                           title="is_edit",
     *                           example=true,
     *                           type="boolean"
     *                       ),
     *                       @OA\Property(
     *                           property="is_delete",
     *                           title="is_delete",
     *                           example=true,
     *                           type="boolean"
     *                       ),
     *                       @OA\Property(
     *                           property="is_read",
     *                           title="is_read",
     *                           example=true,
     *                           type="boolean"
     *                       ),
     *                      ),
     *                    @OA\Property(
     *                      property="created_at",
     *                      title="Created",
     *                      example="2020-09-18 11:42:35",
     *                      type="timestamp"
     *                    ),
     *                  @OA\Property(
     *                      property="updated_at",
     *                      title="Updated",
     *                      example="2020-09-18 11:42:35",
     *                      type="timestamp"
     *                  ),
     *                  @OA\Property(
     *                    property="message_status",
     *                    title="Get Message status",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/ChatMessageStatus"
     *                    )
     *                  ),

     *                 ),
     *                ),
     *                @OA\Property(
     *                    property="total",
     *                    title="Total Data",
     *                    type="integer",
     *                  ),
     *                @OA\Property(
     *                    property="total_page",
     *                    title="Total Page",
     *                    type="integer",
     *                  ),
     *
     *              ),
     *           @OA\Property(
     *                    property="body_not_reads",
     *                    title="Get Table Data Body History",
     *                    @OA\Items(
     *                 @OA\Property(
     *                    property="id",
     *                    title="ID",
     *                    example=1,
     *                    type="int"
     *                     ),
     *               @OA\Property(
     *                    property="sender_id",
     *                    title="Sender ID",
     *                    example=1,
     *                    type="int"
     *               ),
     *               @OA\Property(
     *                    property="chat_id",
     *                    title="Chat ID",
     *                    example=1,
     *                    type="int"
     *               ),
     *                 @OA\Property(
     *                    property="text",
     *                    title="Text",
     *                    example="Hello World",
     *                    type="string"
     *               ),
     *               @OA\Property(
     *                    property="params",
     *                       @OA\Property(
     *                           property="is_edit",
     *                           title="is_edit",
     *                           example=true,
     *                           type="boolean"
     *                       ),
     *                       @OA\Property(
     *                           property="is_delete",
     *                           title="is_delete",
     *                           example=true,
     *                           type="boolean"
     *                       ),
     *                       @OA\Property(
     *                           property="is_read",
     *                           title="is_read",
     *                           example=true,
     *                           type="boolean"
     *                       ),
     *                      ),
     *                    @OA\Property(
     *                      property="created_at",
     *                      title="Created",
     *                      example="2020-09-18 11:42:35",
     *                      type="timestamp"
     *                    ),
     *                  @OA\Property(
     *                      property="updated_at",
     *                      title="Updated",
     *                      example="2020-09-18 11:42:35",
     *                      type="timestamp"
     *                  ),
     *                  @OA\Property(
     *                    property="message_status",
     *                    title="Get Message status",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/ChatMessageStatus"
     *                    )
     *                  ),

     *                 ),
     *              ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function getChat($domain, $id): JsonResponse
    {
        return $this->successResponse($this->chatService->getChat($id));
    }

    /**
     * @OA\Post(
     *      path="/chats/read-messages",
     *      tags={"Chats"},
     *      summary="Chats read messages",
     *      @OA\RequestBody(
     *          description="Chats read messages",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="data",
     *                    @OA\Items(
     *                       @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=5,
     *                       ),
     *                  )
     *               )
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="data",
     *                    @OA\Items(
     *                          ref="#/components/schemas/ChatMessageWithUsersMessageStatus"
     *                      )
     *              ),
     *              @OA\Property(
     *                  property="socket_event",
     *                  example=".private.read_message"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function readMessages(): JsonResponse
    {
        return $this->successResponse($this->chatService->readMessages());
    }

    /**
     * @OA\Put(
     *      path="/chats/like-message/{id}",
     *      tags={"Chats"},
     *      summary="Put like message",
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="is_like",
     *                       @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=5,
     *                       ),
     *                       @OA\Property(
     *                          property="is_like",
     *                          type="boolean",
     *                          example=true,
     *                       ),
     *              ),
     *              @OA\Property(
     *                    property="message",
     *                   ref="#/components/schemas/ChatMessageWithUsersMessageStatus"
     *              ),
     *              @OA\Property(
     *                  property="socket_event",
     *                  example=".private.like_message"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function likeMessage($domain, $id): JsonResponse
    {
        return $this->successResponse($this->chatService->likeMessage($id));
    }

    /**
     * @OA\Put(
     *      path="/chats/update-chat-options/{id}",
     *      tags={"Chats"},
     *      summary="Update Chat Options",
     *      @OA\RequestBody(
     *          description="Chats Chat Options",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="boolean",
     *                    property="is_delete",
     *                    example=true,
     *               ),
     *               @OA\Property(
     *                    type="boolean",
     *                    property="is_notification",
     *                    example=true,
     *               ),
     *              @OA\Property(
     *                    type="boolean",
     *                    property="is_fixed",
     *                    example=true,
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function updateChatOptions($domain, $id): JsonResponse
    {
        return $this->successResponse($this->chatService->updateChatOptions($id));
    }

    /**
     * @OA\Put(
     *      path="/chats/update-chat/{id}",
     *      tags={"Chats"},
     *      summary="Update Chat",
     *      @OA\RequestBody(
     *          description="Chats Chat",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    property="name",
     *                    title="Name",
     *                    example="Name",
     *                    type="string"
     *               ),
     *               @OA\Property(
     *                    property="logo",
     *                    title="Logo",
     *                    example="logo.png",
     *                    type="string"
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Table Data Suppliers",
     *                 @OA\Property(
     *                    property="chat",
     *                    title="Get Chat",
     *                     ref="#/components/schemas/Chat"
     *                  ),
     *                  @OA\Property(
     *                    property="chat_users",
     *                    title="Get Chat Users",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/ChatUser"
     *                    )
     *                  ),
     *              ),
     *              @OA\Property(
     *                  property="socket_event",
     *                  example=".private.store_chat"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function updateChat($domain, $id): JsonResponse
    {
        return $this->successResponse($this->chatService->updateChat($id));
    }

    /**
     * @OA\Post(
     *      path="/chats/add-users-to-chat",
     *      tags={"Chats"},
     *      summary="Add users to chat",
     *      @OA\RequestBody(
     *          description="Add users to chat",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="users",
     *                    @OA\Items(
     *                       @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=5,
     *                       ),
     *                  )
     *               )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Table Data Suppliers",
     *                 @OA\Property(
     *                    property="chat",
     *                    title="Get Chat",
     *                     ref="#/components/schemas/Chat"
     *                  ),
     *                  @OA\Property(
     *                    property="chat_users",
     *                    title="Get Chat Users",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/ChatUser"
     *                    )
     *                  ),
     *              ),
     *              @OA\Property(
     *                  property="socket_event",
     *                  example=".private.send_message"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function addUsersToChat($domain, $id): JsonResponse
    {
        return $this->successResponse($this->chatService->addUsersToChat($id));
    }

    /**
     * @OA\Get(
     *      path="/chats/get-content-chat/{id}",
     *      tags={"Chats"},
     *      summary="Get content chat",
     *      @OA\RequestBody(
     *          description="Get content chat",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="string",
     *                    property="params",
     *                    example="files_id, media_id, links",
     *               )
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="data",
     *                    @OA\Items(
     *                @OA\Property(
     *                    property="id",
     *                    title="ID",
     *                    example=1,
     *                    type="int"
     *               ),
     *               @OA\Property(
     *                    property="params",
     *                       @OA\Property(
     *                           property="files_id",
     *                           title="files_id",
     *                           example={1,2},
     *                       ),
     *                      @OA\Property(
     *                           property="media_id",
     *                           title="files_id",
     *                           example={1,2},
     *                       ),
     *                      @OA\Property(
     *                           property="links",
     *                           title="files_id",
     *                           example={"https://link.ua","https://link2.ua"},
     *                       ),
     *               ),
     *              ),
     *          )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function getContentChat($domain, $id): JsonResponse
    {
        return $this->successResponse($this->chatService->getContentChat($id));
    }

    /**
     * @OA\Get(
     *      path="/chats/get-content-user/{id}",
     *      tags={"Chats"},
     *      summary="Get content user",
     *      @OA\RequestBody(
     *          description="Get content user",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="string",
     *                    property="params",
     *                    example="files_id, media_id, links",
     *               )
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="data",
     *                    @OA\Items(
     *                @OA\Property(
     *                    property="id",
     *                    title="ID",
     *                    example=1,
     *                    type="int"
     *               ),
     *               @OA\Property(
     *                    property="params",
     *                       @OA\Property(
     *                           property="files_id",
     *                           title="files_id",
     *                           example={1,2},
     *                       ),
     *                      @OA\Property(
     *                           property="media_id",
     *                           title="files_id",
     *                           example={1,2},
     *                       ),
     *                      @OA\Property(
     *                           property="links",
     *                           title="files_id",
     *                           example={"https://link.ua","https://link2.ua"},
     *                       ),
     *               ),
     *              ),
     *          )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function getContentUser($domain, $id): JsonResponse
    {
        return $this->successResponse($this->chatService->getContentUser($id));
    }

//    public function searchChats(): JsonResponse
//    {
//        return $this->successResponse($this->chatService->searchChats());
//    }
}


