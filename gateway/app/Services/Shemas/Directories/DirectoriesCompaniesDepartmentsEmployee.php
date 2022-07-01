<?php

namespace App\Services\Shemas\Directories;

/**
 * @OA\Schema(
 *     title="DirectoriesCompaniesDepartmentsEmployee",
 *     description="DirectoriesCompaniesDepartmentsEmployee Shemas",
 *     @OA\Xml(
 *         name="DirectoriesCompaniesDepartmentsEmployee"
 *     )
 * )
 * @OA\Property(
 *     property="employee_id",
 *     title="employee_id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="is_leader",
 *     title="is_leader",
 *     example=true,
 *     type="boolean"
 * )
 * @OA\Property(
 *     property="first_name",
 *     title="first_name",
 *     example="First Name",
 *     type="string"
 * )
 * @OA\Property(
 *     property="last_name",
 *     title="last_name",
 *     example="Last Name",
 *     type="string"
 * )
 * @OA\Property(
 *     property="middle_name",
 *     title="middle_name",
 *     example="Middle Name",
 *     type="string"
 * )
 * @OA\Property(
 *     property="position",
 *     title="position",
 *     example="Директор",
 *     type="string"
 * )
 */

class DirectoriesCompaniesDepartmentsEmployee
{

}
