<?php

namespace App\Services\Shemas\Products;

/**
 * @OA\Schema(
 *     title="Categories",
 *     description="Categories Shemas",
 *     @OA\Xml(
 *         name="Categories"
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
 *     property="desc",
 *     title="Description",
 *     example="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua",
 *     type="string"
 * )
 * @OA\Property(
 *     property="image",
 *     title="Image",
 *     example="images/name.png",
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
 *     property="unit_id",
 *     title="Unit Id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="identifier_fullness",
 *     title="Identifier fullness",
 *     example=100,
 *     type="int"
 * )
 *
 * @OA\Property(
 *     property="identifier_successful",
 *     title="Identifier successful",
 *     example=100,
 *     type="int"
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
 *     property="children",
 *     type="array",
 *     collectionFormat="multi",
 *     @OA\Items(
 *       @OA\Property(
 *           property="id",
 *           title="Id",
 *           example=2,
 *           type="int"
 *       ),
 *       @OA\Property(
 *           property="parent_id",
 *           title="Parent Id",
 *           example=1,
 *           type="int"
 *       ),
 *       @OA\Property(
 *           property="title",
 *           title="Title",
 *           example="Title",
 *           type="string"
 *       ),
 *       @OA\Property(
 *           property="desc",
 *           title="Description",
 *           example="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua",
 *           type="string"
 *       ),
 *       @OA\Property(
 *           property="image",
 *           title="Image",
 *           example="images/name.png",
 *           type="string"
 *       ),
 *       @OA\Property(
 *           property="order",
 *           title="Order",
 *           example=0,
 *           type="int"
 *       ),
 *       @OA\Property(
 *           property="archive",
 *           title="Archive",
 *           example=0,
 *           type="int"
 *       ),
 *      @OA\Property(
 *          property="identifier_fullness",
 *          title="Identifier fullness",
 *          example=100,
 *          type="int"
 *      ),
 *      @OA\Property(
 *          property="identifier_successful",
 *          title="Identifier successful",
 *          example=100,
 *          type="int"
 *       ),
 *       @OA\Property(
 *           property="created_at",
 *           title="Created",
 *           example="2020-09-18 11:42:35",
 *           type="timestamp"
 *       ),
 *       @OA\Property(
 *           property="updated_at",
 *           title="Updated",
 *           example="2020-09-18 11:42:35",
 *           type="timestamp"
 *       ),
 *       @OA\Property(
 *           property="children",
 *           type="array",
 *           collectionFormat="multi",
 *           @OA\Items(
 *               @OA\Property(
 *               )
 *           )
 *       )
 *     ),
 *  )
 */

class Categories
{

}
