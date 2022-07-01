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
 *     property="title_country",
 *     title="Country",
 *     example="country",
 *     type="string"
 * )
 * @OA\Property(
 *     property="title_region",
 *     title="Region",
 *     example="Region",
 *      type="string"
 * )
 * @OA\Property(
 *     property="title_city",
 *     title="City",
 *     example="City",
 *     type="string"
 * )
 * @OA\Property(
 *     property="title_warehouse_type",
 *     title="warehouse type",
 *      example="Розничный",
 *     type="string"
 * )
 * @OA\Property(
 *     property="title_warehouse_groups",
 *     title="warehouse group",
 *     example="warehouse",
 *     type="string"
 * )
 * @OA\Property(
 *     property="title_employee",
 *     title="employee",
 *     example="fullname",
 *     type="string"
 * )
 *  @OA\Property(
 *     property="created_at",
 *     title="Created",
 *     example="2020-09-18 11:42:35",
 *     type="timestamp"
 * )
 */

class WarehouseFinished
{

}
