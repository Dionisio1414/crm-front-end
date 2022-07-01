<?php

namespace App\Services\Shemas\Products;

/**
 * @OA\Schema(
 *     title="WarehouseGroup",
 *     description="WarehouseGroup Shemas",
 *     @OA\Xml(
 *         name="WarehouseGroup"
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
 *     property="archive",
 *     title="archive",
 *     example=0,
 *     type="int"
 * )
 *  * @OA\Property(
 *     property="order",
 *     title="order",
 *     example=0,
 *     type="int"
 * )
 *  @OA\Property(
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

class WarehouseGroup
{

}
