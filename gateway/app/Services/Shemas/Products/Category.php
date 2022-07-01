<?php

namespace App\Services\Shemas\Products;

/**
 * @OA\Schema(
 *     title="Category",
 *     description="Category Shemas",
 *     @OA\Xml(
 *         name="Category"
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
 *     property="order",
 *     title="Order",
 *     example=0,
 *     type="int"
 * )
 *  * @OA\Property(
 *     property="archive",
 *     title="Archive",
 *     example=0,
 *     type="int"
 * )
 * @OA\Property(
 *     property="identifier_fullness",
 *     title="Identifier fullness",
 *     example=100,
 *     type="int"
 * )
 * @OA\Property(
 *     property="identifier_successful",
 *     title="Identifier successful",
 *     example=100,
 *     type="int"
 * )
 * @OA\Property(
 *     property="unit_id",
 *     title="Unit Id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="unit_title",
 *     title="Unit Title",
 *     example="шт",
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
 * @OA\Property(
 *     property="properties",
 *     type="array",
 *     collectionFormat="multi",
 *     @OA\Items(
 *          ref="#/components/schemas/PropertiesWithPropertyValue"
 *     ),
 *  )
 * @OA\Property(
 *     property="characteristics",
 *     type="array",
 *     collectionFormat="multi",
 *     @OA\Items(
 *          ref="#/components/schemas/CharacteristicsWithCharacteristicValue"
 *     ),
 *  )
 * @OA\Property(
 *     property="unit",
 *     ref="#/components/schemas/DirectorySelected"
 *  )
 *  @OA\Property(
 *                 property="image",
 *                 title="Get images",
 *                  @OA\Property(
 *                    property="id",
 *                    title="Id",
 *                    type="integer",
 *                  ),
 *                  @OA\Property(
 *                    property="category_id",
 *                    title="Category id",
 *                    type="integer",
 *                  ),
 *                  @OA\Property(
 *                    property="title",
 *                    title="title",
 *                    type="string",
 *                  ),
 *                  @OA\Property(
 *                    property="is_image",
 *                    title="is image",
 *                    type="boolean",
 *                  ),
 *                  @OA\Property(
 *                    property="url",
 *                    title="url",
 *                    type="string",
 *                  )
 * )
 *
 */

class Category
{

}
