<?php

namespace App\Services\Shemas\Directories;

/**
 * @OA\Schema(
 *     title="DirectorySelected",
 *     description="DirectorySelected Shemas",
 *     @OA\Xml(
 *         name="Directories"
 *     )
 * )
 * @OA\Property(
 *     property="id",
 *     title="Id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="is_editable",
 *     title="is_editable",
 *     example=true,
 *     type="boolean"
 * )
 * @OA\Property(
 *     property="is_default",
 *     title="is_default",
 *     example=false,
 *     type="boolean"
 * )
 * @OA\Property(
 *     property="title",
 *     @OA\Items(
 *         @OA\Property(
 *            property="code",
 *            type="string",
 *            example="ru",
 *         ),
 *         @OA\Property(
 *             property="title",
 *             type="string",
 *             example="title",
 *          ),
 *     ),
 * ),
 */

class DirectorySelected
{

}
