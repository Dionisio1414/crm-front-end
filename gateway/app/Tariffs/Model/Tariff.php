<?php

namespace App\Tariffs\Model;

use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     title="Tariff",
 *     description="Tariffs model",
 *     @OA\Xml(
 *         name="Tariff"
 *     )
 * )
 * @OA\Property(
 *     property="id",
 *     title="Id",
 *     example=100,
 *     type="int"
 * )
 * @OA\Property(
 *     property="cost",
 *     title="Cost",
 *     example=10000,
 *     type="int"
 * )
 */

class Tariff extends Model
{

    protected $fillable = ['cost', 'custom_column', 'steps'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'steps'];

    protected $casts = [
        'custom_column' => 'json',
        'steps' => 'json',
    ];

    protected $with = ['detail'];

    public function detail()
    {
        return $this->hasMany(Detail::class, 'tariff_id', 'id');
    }

    public function getCustomColumnAttribute($value)
    {
        return array_values(json_decode($value, true) ?: []);
    }
}
