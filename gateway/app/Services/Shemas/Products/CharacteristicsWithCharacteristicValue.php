<?php

namespace App\Services\Shemas\Products;

/**
 * @OA\Schema(
 *     title="Characteristics",
 *     description="Categories Shemas",
 *     @OA\Xml(
 *         name="Characteristics"
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
 *     property="order",
 *     title="Order",
 *     example=0,
 *     type="int"
 * )
 * @OA\Property(
 *     property="archive",
 *     title="Archive",
 *     example=0,
 *     type="int"
 * )
 * @OA\Property(
 *     property="code",
 *     title="Code",
 *     example="id1234",
 *     type="string"
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
 *  @OA\Property(
 *     property="characteristic_value",
 *     type="array",
 *     collectionFormat="multi",
 *     @OA\Items(
 *          ref="#/components/schemas/CharacteristicValue"
 *     ),
 *  )
 */

class CharacteristicsWithCharacteristicValue
{

}
