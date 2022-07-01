<?php

namespace App\Tariffs\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="StepDetail",
 *     description="Steps Items",
 *     @OA\Xml(
 *         name="StepDetail"
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
 *     title="Name",
 *     example="Name",
 *     type="string"
 * )
 * @OA\Property(
 *     property="value",
 *     title="Value",
 *     example=1,
 *     type="int"
 * )
 */

class StepDetail extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['step_id', 'title', 'value'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['step_id', 'created_at', 'updated_at'];

    protected $casts = ['title' => 'json'];
}
