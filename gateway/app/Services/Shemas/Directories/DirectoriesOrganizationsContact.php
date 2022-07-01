<?php

namespace App\Services\Shemas\Directories;

/**
 * @OA\Schema(
 *     title="DirectoriesOrganizationsContact",
 *     description="DirectoriesOrganizationsContact Shemas",
 *     @OA\Xml(
 *         name="DirectoriesOrganizationsContact"
 *     )
 * )
 * @OA\Property(
 *     property="legal_country_id",
 *     title="legal_country_id",
 *     example=3,
 *     type="integer"
 * )
 * @OA\Property(
 *     property="legal_region_id",
 *     title="legal_region_id",
 *     example=3,
 *     type="integer"
 * )
 * @OA\Property(
 *     property="legal_city_id",
 *     title="legal_city_id",
 *     example=4,
 *     type="integer"
 * )
 * @OA\Property(
 *     property="legal_city",
 *     title="Legal City",
 *     example="Legal City",
 *     type="string"
 * )
 *  @OA\Property(
 *     property="legal_number_house",
 *     title="legal_number_house",
 *     example=38,
 *     type="integer"
 * )
 *  @OA\Property(
 *     property="is_legal_equal_actual",
 *     title="is_legal_equal_actual",
 *     example=true,
 *     type="boolean"
 * )
 * @OA\Property(
 *     property="values",
 *     title="Values",
 *     @OA\Items(
 *      ref="#/components/schemas/DirectoriesOrganizationsContactAdresses"
 *     )
 * )
 */

class DirectoriesOrganizationsContact
{

}
