<?php

namespace App\Services\Shemas\Products;

/**
 * @OA\Schema(
 *     title="Warehouses",
 *     description="Warehouses Shemas",
 *     @OA\Xml(
 *         name="Warehouses"
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
 *@OA\Property(
 *     property="phone",
 *     title="phone",
 *     example="[+380999999999, +380999999999]",
 *     type="string"
 * )
 * @OA\Property(
 *     property="street",
 *     title="street",
 *     example="улица",
 *     type="string"
 * )
 * @OA\Property(
 *     property="number_house",
 *     title="street",
 *     example="48-б",
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
 * @OA\Property(
 *     property="country_id",
 *     title="Country Id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="region_id",
 *     title="Region Id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="city_id",
 *     title="City Id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="warehouse_type_id",
 *     title="warehouse type id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="warehouse_group_id",
 *     title="warehouse group id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="employee_id",
 *     title="employee id",
 *     example=1,
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

class Warehouses
{

}
