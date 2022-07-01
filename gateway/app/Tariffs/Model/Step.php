<?php

namespace App\Tariffs\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Steps",
 *     description="Steps model",
 *     @OA\Xml(
 *         name="Steps"
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
 *     title="Cost",
 *     example="Name Step",
 *     type="string"
 * )
 * @OA\Property(
 *     property="step_number",
 *     title="Step Number",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="items",
 *     title="Items",
 *     type="array",
 *     @OA\Items(
 *     ref="#/components/schemas/StepDetail"
 *     )
 * )
 */

class Step extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'step_number'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    protected $with = ['items'];

    public function items()
    {
        return $this->hasMany(StepDetail::class, 'step_id', 'id');
    }
}
