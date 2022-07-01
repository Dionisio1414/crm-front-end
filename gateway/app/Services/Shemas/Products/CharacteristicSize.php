<?php

namespace App\Services\Shemas\Products;

/**
 * @OA\Schema(
 *     title="GetCharacteristic Color",
 *     description="Get Characteristic Color",
 *     @OA\Xml(
 *         name="GetCharacteristic Color"
 *     )
 * )
 *
 * @OA\Property(
 *     property="title",
 *     title="Title",
 *     example="title",
 *     type="string"
 * )
 * @OA\Property(
 *     property="order",
 *     title="Order",
 *     example=3,
 *     type="int"
 * )
 * @OA\Property(
 *     property="archive",
 *     title="Archive",
 *     example=0,
 *     type="int"
 * )
 * @OA\Property(
 *     property="created_at",
 *     title="Created",
 *     example="2020-09-18 11:42:35",
 *     type="timestamp"
 * )
 * @OA\Property(
 *     property="updated_at",
 *     title="Updated",
 *     example="2020-09-18 11:42:35",
 *     type="timestamp"
 * )
 * @OA\Property(
 *     property="id",
 *     title="Id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="characteristic_value",
 *     type="array",
 *     collectionFormat="multi",
 *     @OA\Items(
 *          ref="#/components/schemas/CharacteristicValue"
 *     ),
 * )
 * @OA\Property(
 *     property="characteristic_size_value",
 *     type="array",
 *     collectionFormat="multi",
 *     @OA\Items(
 *          ref="#/components/schemas/CharacteristicSizeValue"
 *     ),
 * )
 * @OA\Property(
 *     property="categories",
 *     type="array",
 *     collectionFormat="multi",
 *     @OA\Items(
 *          ref="#/components/schemas/CategoriesWithoutParent"
 *     ),
 * )
 *
 */

class CharacteristicSize
{

}
