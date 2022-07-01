<?php

namespace App\Services\Shemas\Chats;

/**
 * @OA\Schema(
 *     title="ChatUser",
 *     description="ChatUser Shemas",
 *     @OA\Xml(
 *         name="ChatUser"
 *     )
 * )
 * @OA\Property(
 *     property="id",
 *     title="Id",
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
 *     property="user_id",
 *     title="user ID",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="is_delete",
 *     title="Is Delete",
 *     example=true,
 *     type="boolean"
 * )
 * @OA\Property(
 *     property="is_notification",
 *     title="Is Notification",
 *     example=true,
 *     type="boolean"
 * )
 * @OA\Property(
 *     property="is_fixed",
 *     title="Is fixed",
 *     example=true,
 *     type="boolean"
 * )
 */

class ChatUser
{

}
