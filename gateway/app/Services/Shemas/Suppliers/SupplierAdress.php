<?php

namespace App\Services\Shemas\Suppliers;

/**
 * @OA\Schema(
 *     title="SupplierAdress",
 *     description="SupplierAdress Shemas",
 *     @OA\Xml(
 *         name="SupplierAdress"
 *     )
 * )
 * @OA\Property(
 *     property="street",
 *     title="street",
 *     example="Street",
 *     type="string"
 * )
 * @OA\Property(
 *     property="number_housing",
 *     title="number_housing",
 *     example="23",
 *     type="string"
 * )
 * @OA\Property(
 *     property="country_id",
 *     title="country_id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="region_id",
 *     title="region_id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="city_id",
 *     title="city_id",
 *     example=1,
 *     type="int"
 * )
 */

class SupplierAdress
{

}
