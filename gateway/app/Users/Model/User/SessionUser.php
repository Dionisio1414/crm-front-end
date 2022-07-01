<?php
namespace App\Users\Model\User;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="SessionUser",
 *     description="SessionUser model",
 *     @OA\Xml(
 *         name="SessionUser"
 *     )
 * )
 * @OA\Property(
 *     property="ip",
 *     title="Ip",
 *     example="192.0.0.1",
 *     type="string"
 * )
 * @OA\Property(
 *     property="user_agent",
 *     title="User Agent",
 *     example="FIrefox",
 *     type="string"
 * )
 * @OA\Property(
 *     property="token",
 *     title="User Token",
 *     example="string",
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
 */

class SessionUser extends Model
{

    protected $fillable = ['user_id', 'ip', 'user_agent', 'token_id', 'login_date', 'expires_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id'
    ];
}
