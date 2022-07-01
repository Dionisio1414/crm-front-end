<?php

namespace App\Services\Shemas\Products;

/**
 * @OA\Schema(
 *     title="Characteristic Size Value",
 *     description="Categories Shemas",
 *     @OA\Xml(
 *         name="CharacteristicSizeValue"
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
 * @OA\Property(
 *     property="check",
 *     title="check",
 *     example="0",
 *     type="boolean"
 * )
 * @OA\Property(
 *     property="type",
 *     title="type",
 *     example="boolean(0 - sizeNumber, 1 - sizeString)",
 *     type="boolean"
 * )
 */

class CharacteristicSizeValue
{

}
