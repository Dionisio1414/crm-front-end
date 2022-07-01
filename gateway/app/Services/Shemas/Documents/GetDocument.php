<?php

namespace App\Services\Shemas\Documents;

/**
 * @OA\Schema(
 *     title="Documents",
 *     description="Documents Shemas",
 *     @OA\Xml(
 *         name="Documents"
 *     )
 * )
 * @OA\Property(
 *     property="id",
 *     title="Id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="organization_convert_id",
 *     title="organization convert id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="receipt_date_scheduled",
 *     title="receipt date scheduled",
 *     example="2020-12-16",
 *     type="date"
 * )
 * @OA\Property(
 *     property="warehouse",
 *     title="Warehouse",
 *     example="Warehouse",
 *     type="string"
 * )
 * @OA\Property(
 *     property="organization",
 *     title="Organization",
 *     example="Organization",
 *     type="string"
 * )
 * @OA\Property(
 *     property="currency",
 *     title="Currency",
 *     example="$",
 *     type="string"
 * )
 * @OA\Property(
 *     property="employee",
 *     title="employee",
 *     example="Full name",
 *     type="string"
 * )
 * @OA\Property(
 *     property="created_at",
 *     title="created at",
 *     example="2020-12-16",
 *     type="date"
 * )
 * @OA\Property(
 *     property="sum_price",
 *     title="sum price",
 *     example=100,
 *     type="int"
 * )
 * @OA\Property(
 *     property="sum_balance",
 *     title="sum balance",
 *     example=50,
 *     type="int"
 * )
 * @OA\Property(
 *     property="comments",
 *     title="Сomments",
 *     example="comments",
 *     type="string"
 * )
 * @OA\Property(
 *                 property="nomenclatures",
 *                 title="Get Documents in Warehouse",
 *                 @OA\Property(
 *                    property="headers",
 *                    title="Get Table Data Header",
 *                    type="array",
 *                    collectionFormat="multi",
 *                    @OA\Items(
 *                         ref="#/components/schemas/DirectoriesHeader"
 *                    )
 *                  ),
 *                  @OA\Property(
 *                    property="body",
 *                    title="Get Table Data Body",
 *                    type="array",
 *                    collectionFormat="multi",
 *                    @OA\Items(
 *                         ref="#/components/schemas/Directories"
 *                    )
 *                  ),
 *                )
 */
class GetDocument
{

}
