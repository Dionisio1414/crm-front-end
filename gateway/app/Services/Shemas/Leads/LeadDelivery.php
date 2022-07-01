<?php

namespace App\Services\Shemas\Leads;

/**
 * @OA\Schema(
 *     title="LeadDelivery",
 *     description="LeadDelivery Shemas",
 *     @OA\Xml(
 *         name="LeadNomenclatures"
 *     )
 * )
 * @OA\Property(
 *     property="delivery_methods_id",
 *     title="delivery_methods_id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="type_deliveries_id",
 *     title="type_deliveries_id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="department_city_id",
 *     title="department_city_id",
 *     example=1,
 *     type="int"
 * )
 *@OA\Property(
 *     property="department_id",
 *     title="department_id",
 *     example=1,
 *     type="int"
 * )
 *  *@OA\Property(
 *     property="ttn_number",
 *     title="ttn_number",
 *     example="543543543",
 *     type="string"
 * )
 *  *@OA\Property(
 *     property="ttn_date",
 *     title="ttn_date",
 *     example="2021-03-28 10:01",
 *     type="string"
 * )
 */

class LeadDelivery
{

}
