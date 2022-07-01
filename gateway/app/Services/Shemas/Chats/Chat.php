<?php

namespace App\Services\Shemas\Chats;

/**
 * @OA\Schema(
 *     title="Chat",
 *     description="Chat Shemas",
 *     @OA\Xml(
 *         name="Chat"
 *     )
 * )
 * @OA\Property(
 *     property="id",
 *     title="Id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="name",
 *     title="name",
 *     type="string"
 * )
 * @OA\Property(
 *     property="logo",
 *     title="logo",
 *     type="string"
 * )
 * @OA\Property(
 *     property="is_group_chat",
 *     title="is_group_chat",
 *     type="boolean"
 * )
 * @OA\Property(
 *     property="author_id",
 *     title="Id",
 *     example=1,
 *     type="int"
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
 * @OA\Property(
 *     property="action",
 *     title="action",
 *     type="string",
 *     example="create privat chat"
 * )
 * @OA\Property(
 *     property="message",
 *     title="message",
 *     ref="#/components/schemas/ChatMessage"
 * )
 */

class Chat
{

}
