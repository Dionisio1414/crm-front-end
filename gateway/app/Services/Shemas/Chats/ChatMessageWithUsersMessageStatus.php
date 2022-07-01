<?php

namespace App\Services\Shemas\Chats;

/**
 * @OA\Schema(
 *     title="ChatMessageWithUsersMessageStatus",
 *     description="ChatMessageWithUsersMessageStatus Shemas",
 *     @OA\Xml(
 *         name="ChatMessageWithUsersMessageStatus"
 *     )
 * )
 * @OA\Property(
 *     property="id",
 *     title="Id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="sender_id",
 *     title="sender ID",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="chat_id",
 *     title="chat ID",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="text",
 *     title="text",
 *     type="string"
 * )
 * @OA\Property(
 *     property="params",
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
 * )
 * @OA\Property(
 *     property="created_at",
 *     title="Created",
 *     example="2020-09-18 11:42:35",
 *     type="timestamp"
 * )
 *  @OA\Property(
 *     property="updated_at",
 *     title="Updated",
 *     example="2020-09-18 11:42:35",
 *     type="timestamp"
 * )
 *  @OA\Property(
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
 *                    ref="#/components/schemas/ChatMessageStatus"
 *                  ),
 */

class ChatMessageWithUsersMessageStatus
{

}
