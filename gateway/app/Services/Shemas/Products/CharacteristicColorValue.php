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
 * @OA\Property(
 *     property="code",
 *     title="Code",
 *     example="code",
 *     type="string"
 * )
 * @OA\Property(
 *     property="color",
 *     title="color",
 *     example="#111",
 *     type="string"
 * )
 *  @OA\Property(
 *                 property="image",
 *                 title="Get images",
 *                  @OA\Property(
 *                    property="id",
 *                    title="Id",
 *                    type="integer",
 *                  ),
 *                  @OA\Property(
 *                    property="category_id",
 *                    title="Category id",
 *                    type="integer",
 *                  ),
 *                  @OA\Property(
 *                    property="title",
 *                    title="title",
 *                    type="string",
 *                  ),
 *                  @OA\Property(
 *                    property="is_image",
 *                    title="is image",
 *                    type="boolean",
 *                  ),
 *                  @OA\Property(
 *                    property="url",
 *                    title="Category id",
 *                    type="string",
 *                  ),
 *              )
 */

class CharacteristicColorValue
{

}
