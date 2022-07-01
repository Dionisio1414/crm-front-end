<?php

namespace App\Services\Shemas\Products;

/**
 * @OA\Schema(
 *     title="ResponseGenerateProducts",
 *     description="ResponseGenerateProducts Shemas",
 *     @OA\Xml(
 *         name="ResponseGenerateProducts"
 *     )
 * )
 * @OA\Property(
 *     property="name",
 *     title="name",
 *     example="Test",
 *     type="string"
 * )
 * @OA\Property(
 *     property="sku",
 *     title="sku",
 *     example="DFG32",
 *     type="string"
 * )
 * @OA\Property(
 *     property="price",
 *     title="price",
 *          @OA\Property(
 *              property="id",
 *              title="Id",
 *              example=1,
 *              type="int"
 *          ),
 *          @OA\Property(
 *              property="value",
 *              title="Value",
 *              example="Test",
 *              type="string"
 *          )
 * )
 * @OA\Property(
 *     property="base_characteristic_value",
 *     title="base_characteristic_value",
 *     @OA\Property(
 *              property="values",
 *              title="values",
 *              example="int/string/objects",
 *              type="string"
 *    )
 * )
 * @OA\Property(
 *     property="characteristic_value",
 *     title="characteristic_value",
 *     type="array",
 *     collectionFormat="multi",
 *     @OA\Items(
 *      @OA\Property(
 *              property="values",
 *              title="values",
 *              example="int/string/objects",
 *              type="string"
 *      )
 *    )
 * )
 * @OA\Property(
 *     property="characteristic_color_value",
 *     title="characteristic_color_value",
 *     @OA\Property(
 *              property="values",
 *              title="values",
 *              example="int/string/objects",
 *              type="string"
 *    )
 * )
 */

class ResponseGenerateProducts
{

}
