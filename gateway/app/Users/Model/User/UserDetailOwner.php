<?php

namespace App\Users\Model\User;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="UserDetailOwner",
 *     description="User Detail Owner",
 *     @OA\Xml(
 *         name="UserDetailOwner"
 *     )
 * )
 * @OA\Property(
 *     property="id",
 *     title="Id",
 *     example=100,
 *     type="int"
 * )
 * @OA\Property(
 *     property="onboarding_tariff",
 *     title="Onboarding first passed",
 *     example=true,
 *     type="boolean"
 * )
 * @OA\Property(
 *     property="onboarding_edit",
 *     title="Onboarding second passed",
 *     example=true,
 *     type="boolean"
 * )
 * @OA\Property(
 *     property="is_owner",
 *     title="Is owner",
 *     example=true,
 *     type="boolean"
 * )
 * @OA\Property(
 *     property="password_reset",
 *     title="Password reset",
 *     example=false,
 *     type="boolean"
 * )
 */

class UserDetailOwner extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'onboarding_tariff', 'onboarding_edit', 'password_reset', 'is_owner'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token', 'verification_token', 'user_id', 'created_at', 'updated_at'
    ];

    protected $casts = [
        'onboarding_tariff'=> 'boolean',
        'onboarding_edit'  => 'boolean',
        'password_reset'   => 'boolean',
        'is_owner'         => 'boolean'
    ];
}
