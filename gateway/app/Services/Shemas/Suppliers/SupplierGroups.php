<?php

namespace App\Services\Shemas\Suppliers;

/**
 * @OA\Schema(
 *     title="SupplierGroups",
 *     description="SupplierGroups Shemas",
 *     @OA\Xml(
 *         name="SupplierGroups"
 *     )
 * )
 * @OA\Property(
 *     property="id",
 *     title="Id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="parent_id",
 *     title="Parent Id",
 *     example=0,
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
 *      property="children",
 *      title="Children Group",
 *      type="array",
 *      collectionFormat="multi",
 *      @OA\Items(
 *          @OA\Property(
 *              property="id",
 *              title="Id",
 *              example=1,
 *              type="int"
 *          ),
 *          @OA\Property(
 *              property="parent_id",
 *              title="Parent Id",
 *              example=0,
 *              type="int"
 *          ),
 *          @OA\Property(
 *              property="title",
 *              title="Title",
 *              example="Title",
 *              type="string"
 *          ),
 *          @OA\Property(
 *              property="order",
 *              title="Order",
 *              example=0,
 *              type="int"
 *          ),
 *          @OA\Property(
 *              property="created_at",
 *              title="Created",
 *              example="2020-09-18 11:42:35",
 *              type="timestamp"
 *          ),
 *          @OA\Property(
 *              property="updated_at",
 *              title="Updated",
 *              example="2020-09-18 11:42:35",
 *              type="timestamp"
 *          ),
 *          @OA\Property(
 *           property="children",
 *           type="array",
 *           collectionFormat="multi",
 *           @OA\Items(
 *               @OA\Property(
 *               )
 *           )
 *         )
 *      )
 *  )
 */

class SupplierGroups
{

}
