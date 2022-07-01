<?php

namespace App\Services\Shemas\Directories;

/**
 * @OA\Schema(
 *     title="DirectoriesHeader",
 *     description="DirectoriesHeader Shemas",
 *     @OA\Xml(
 *         name="DirectoriesHeader"
 *     )
 * )
 * @OA\Property(
 *     property="id",
 *     title="Id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="title",
 *     title="Title",
 *     example="Title",
 *     type="string"
 * )
 * @OA\Property(
 *     property="is_sortable",
 *     title="is_sortable",
 *     example=true,
 *     type="boolean"
 * )
 * @OA\Property(
 *     property="is_visible",
 *     title="is_visible",
 *     example=false,
 *     type="boolean"
 * )
 * @OA\Property(
 *     property="order",
 *     title="Order",
 *     example=0,
 *     type="integer"
 * )
 * @OA\Property(
 *     property="value",
 *     title="Value",
 *     example="code",
 *     type="string"
 * )
 */

class DirectoriesHeader
{

}
