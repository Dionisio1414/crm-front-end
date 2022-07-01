<?php

namespace App\Languages\Model;

use Illuminate\Database\Eloquent\Model;
//use App\Core\Model\Yesno;

/**
 * @OA\Schema(
 *     title="Languages",
 *     description="Directories Shemas",
 *     @OA\Xml(
 *         name="Languages"
 *     )
 * )
 * @OA\Property(
 *     property="id",
 *     title="Id",
 *     example=100,
 *     type="int"
 * )
 * @OA\Property(
 *     property="title",
 *     title="Title",
 *     example="Русский",
 *     type="string"
 * )
 * @OA\Property(
 *     property="code",
 *     title="Code",
 *     example="ru",
 *     type="string"
 * )
 * @OA\Property(
 *     property="status",
 *     title="Status",
 *     example=true,
 *     type="boolean"
 * )
 */


class Language extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'code', 'status'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $casts = [
      'status' => 'boolean'
    ];
}
