<?php

namespace App\Services\Shemas\FileManager;

/**
 * @OA\Schema(
 *     title="File Manager",
 *     description="File Manager",
 *     @OA\Xml(
 *         name="FileManager"
 *     )
 * )
 * @OA\Property(
 *     property="id",
 *     title="Id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="category_id",
 *     title="Category Id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="title",
 *     title="Name file",
 *     example="test.png",
 *     type="string"
 * )
 * @OA\Property(
 *     property="path",
 *     title="Path file",
 *     example="site.com/test.png",
 *     type="string"
 * )
 * @OA\Property(
 *     property="full_path",
 *     title="full_path",
 *     example="site.com/test.png",
 *     type="string"
 * )
 * @OA\Property(
 *     property="is_image",
 *     title="is_image",
 *     example="true",
 *     type="boolean"
 * )
 * @OA\Property(
 *     property="updated_at",
 *     title="Date Update",
 *     ref="string"
 * )
 */

class FileManager
{

}
