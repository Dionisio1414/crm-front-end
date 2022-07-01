<?php

namespace App\Services\Shemas\Directories;

/**
 * @OA\Schema(
 *     title="DirectoriesOrganizationsContactAdresses",
 *     description="DirectoriesOrganizationsContactAdresses Shemas",
 *     @OA\Xml(
 *         name="DirectoriesOrganizationsContactAdresses"
 *     )
 * )
 * @OA\Property(
 *     property="phone",
 *     title="phone",
 *     example="380111111111",
 *     type="string"
 * )
 * @OA\Property(
 *     property="email",
 *     title="email",
 *     example="test@example.com",
 *     type="string"
 * )
 * @OA\Property(
 *     property="country_id",
 *     title="country_id",
 *     example=23,
 *     type="integer"
 * )
 * @OA\Property(
 *     property="region_id",
 *     title="region_id",
 *     example=32,
 *     type="integer"
 * )
 *  @OA\Property(
 *     property="city_id",
 *     title="city_id",
 *     example=32,
 *     type="integer"
 * )
 *  @OA\Property(
 *     property="city",
 *     title="city",
 *     example="City",
 *     type="string"
 * )
 *  @OA\Property(
 *     property="number_house",
 *     title="number_house",
 *     example=32,
 *     type="integer"
 * )
 */

class DirectoriesOrganizationsContactAdresses
{

}
