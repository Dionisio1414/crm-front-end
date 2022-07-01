<?php

namespace App\Services\Shemas\Directories;

/**
 * @OA\Schema(
 *     title="Directories",
 *     description="Directories Shemas",
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
 *     property="value_name",
 *     title="value_name",
 *     example="string/boolean/integer/object",
 *     type="string"
 * )
 * @OA\Property(
 *     property="cells",
 *     title="Cells",
 *     ref="#/components/schemas/DirectoriesDetail"
 * )
 */

class Directories
{

}
