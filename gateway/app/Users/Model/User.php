<?php

namespace App\Users\Model;

use App\Core\Model\Yesno;
use App\Tariffs\Model\Subscription;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Users\Model\User\Company;
use App\Users\Model\User\UserDetailOwner;
use App\Users\Model\User\SessionUser;
use \App\Core\Traits\AccessToken;

/**
 * @OA\Schema(
 *     title="User",
 *     description="User model",
 *     @OA\Xml(
 *         name="User"
 *     )
 * )
 * @OA\Property(
 *     property="id",
 *     title="Id",
 *     example=100,
 *     type="int"
 * )
 * @OA\Property(
 *     property="position_id",
 *     title="Position Id",
 *     example=100,
 *     type="int"
 * )
 * @OA\Property(
 *     property="thumbnail_id",
 *     title="Thumbnail Id",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="first_name",
 *     title="First Name",
 *     example="Name",
 *     type="string"
 * )
 * @OA\Property(
 *     property="last_name",
 *     title="Last Name",
 *     example="Surname",
 *     type="string"
 * )
 * @OA\Property(
 *     property="middle_name",
 *     title="Middle Name",
 *     example="Sergeevich",
 *     type="string"
 * )
 * @OA\Property(
 *     property="position",
 *     title="Position",
 *     example="Director",
 *     type="string"
 * )
 * @OA\Property(
 *     property="email",
 *     title="Email",
 *     example="test@example.com",
 *     type="string"
 * )
 * @OA\Property(
 *     property="phone",
 *     title="Phone number",
 *     example="+3801111111",
 *     type="string"
 * )
 * @OA\Property(
 *     property="active_subscription",
 *     title="Company Id",
 *     example=true,
 *     type="boolean"
 * )
 * @OA\Property(
 *     property="social",
 *     title="Social Login",
 *     example=false,
 *     type="boolean"
 * )
 * @OA\Property(
 *     property="language_id",
 *     title="Users Language",
 *     example=12,
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
 * @OA\Property(
 *     property="company",
 *     title="Company",
 *     ref="#/components/schemas/Company"
 * )
 * @OA\Property(
 *     property="detail",
 *     title="Detail User",
 *     ref="#/components/schemas/UserDetailOwner"
 * )
 */

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, AccessToken;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'company_id',
        'role_id',
        'position_id',
        'email',
        'phone',
        'thumbnail_id',
        'first_name',
        'last_name',
        'middle_name',
        'verification_token',
        'social',
        'language_id',
        'password',
        'api_token',
        'auth_flag',
        'session_life_day'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    const ONLINE = 0;
    const NOT_HERE  = 1;
    const NOT_DISTURB  = 2;
    const OFFLINE  = 3;

    protected $hidden = [
        'password', 'remember_token', 'api_token', 'verification_token', 'role_id', 'company_id', 'auth_flag'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'social'            => 'array',
        'auth_flag'         => 'boolean'
    ];

    protected $with = ['company', 'detail'];

    protected $appends = ['active_subscription'];

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function getSocialAttribute($value)
    {
        return json_decode($value);
    }

    public function detail()
    {
        return $this->hasOne(UserDetailOwner::class, 'user_id', 'id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'user_id', 'id');
    }

    public function session()
    {
        return $this->hasOne(SessionUser::class, 'user_id', 'id');
    }

    public function getActiveSubscriptionAttribute()
    {
        return $this->subscriptions()
                    ->orderBy('id', 'desc')
                    ->where('status', Yesno::YES)->first() ? true : false;
    }

    public function getInviteUrlAttribute()
    {
        return config('app.http_shema_main') . config('app.main_domain') . '/login?verification_token=' . $this->verification_token . '&id='. $this->id;
    }
}
