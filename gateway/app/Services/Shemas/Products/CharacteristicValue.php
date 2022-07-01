<?php

namespace App\Services\Shemas\Products;

/**
 * @OA\Schema(
 *     title="CharacteristicValue",
 *     description="Categories Shemas",
 *     @OA\Xml(
 *         name="CharacteristicValue"
 *     )
 * )
 * @OA\Property(
 *     property="id",
 *     title="Id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="characteristic_id",
 *     title="Parent Id",
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
 *     property="order",
 *     title="Order",
 *     example=0,
 *     type="int"
 * )
 */

class CharacteristicValue
{

}
