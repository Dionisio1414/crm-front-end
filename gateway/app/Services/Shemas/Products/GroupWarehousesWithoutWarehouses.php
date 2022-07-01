<?php

namespace App\Services\Shemas\Products;

/**
 * @OA\Schema(
 *     title="GroupWarehousesWithoutWarehouses",
 *     description="GroupWarehousesWithoutWarehouses Shemas",
 *     @OA\Xml(
 *         name="GroupWarehousesWithoutWarehouses"
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
 *     example=true,
 *     type="boolean"
 * )
 * @OA\Property(
 *     property="order",
 *     title="Order",
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
 * @OA\Property(
 *     property="children",
 *     type="array",
 *     collectionFormat="multi",
 *     @OA\Items(
 * @OA\Property(
 *     property="id",
 *     title="Id",
 *     example=1,
 *     type="int"
 * ),
 * @OA\Property(
 *     property="title",
 *     title="Title",
 *     example="Title",
 *     type="string"
 * ),
 *  @OA\Property(
 *     property="archive",
 *     title="archive",
 *     example=true,
 *     type="boolean"
 * ),
 * @OA\Property(
 *     property="order",
 *     title="Order",
 *     example=0,
 *     type="int"
 * ),
 *  @OA\Property(
 *     property="created_at",
 *     title="Created",
 *     example="2020-09-18 11:42:35",
 *     type="timestamp"
 * ),
 *  @OA\Property(
 *     property="updated_at",
 *     title="Updated",
 *     example="2020-09-18 11:42:35",
 *     type="timestamp"
 * ),
 *  @OA\Property(
 *     property="warehouse",
 *     type="array",
 *     collectionFormat="multi",
 *     @OA\Items(
 *          ref="#/components/schemas/Warehouses"
 *     ),
 *  )
 *     ),
 *  )
 *
 */

class GroupWarehousesWithoutWarehouses
{

}
