<?php

namespace App\Services\Shemas\Suppliers;

/**
 * @OA\Schema(
 *     title="SupplierGroup",
 *     description="SupplierGroup Shemas",
 *     @OA\Xml(
 *         name="SupplierGroup"
 *     )
 * )
 * @OA\Property(
 *     property="parent_id",
 *     title="Parent Id",
 *     example=0,
 *     type="int"
 * )
 * @OA\Property(
 *     property="title",
 *     title="Title",
 *     example="Title",
 *     type="string"
 * )
 * @OA\Property(
 *     property="order",
 *     title="Order",
 *     example=0,
 *     type="int"
 * )
 */

class SupplierGroup
{

}
