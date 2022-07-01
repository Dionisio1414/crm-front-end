<?php

namespace App\Services\Shemas\Chats;

/**
 * @OA\Schema(
 *     title="ChatMessageStatus",
 *     description="ChatMessageStatus Shemas",
 *     @OA\Xml(
 *         name="ChatMessageStatus"
 *     )
 * )
 * @OA\Property(
 *     property="id",
 *     title="Id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="message_id",
 *     title="message ID",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="user_id",
 *     title="user ID",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="is_read",
 *     title="is read",
 *     example=true,
 *     type="boolean"
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
 */

class ChatMessageStatus
{

}
