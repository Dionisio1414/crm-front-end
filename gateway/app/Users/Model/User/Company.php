<?php
namespace App\Users\Model\User;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Company",
 *     description="Company model",
 *     @OA\Xml(
 *         name="Company"
 *     )
 * )
 * @OA\Property(
 *     property="id",
 *     title="Id",
 *     example=100,
 *     type="int"
 * )
 * @OA\Property(
 *     property="name",
 *     title="Name",
 *     example="Name",
 *     type="string"
 * )
 * @OA\Property(
 *     property="domain",
 *     title="Domain",
 *     example="test.com",
 *     type="string"
 * )
 * @OA\Property(
 *     property="main_domain",
 *     title="Main Domain",
 *     example="esl.ua",
 *     type="string"
 * )
 * @OA\Property(
 *     property="language_interface_id",
 *     title="Language Interface",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="currency_id",
 *     title="Currency",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="form_payments_id",
 *     title="Form Payments",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="terms_payment_id",
 *     title="Term Payment",
 *     example=1,
 *     type="int"
 * )
 * @OA\Property(
 *     property="archive_cleare",
 *     title="Clearing the archive in days",
 *     example=22,
 *     type="int"
 * )
 * @OA\Property(
 *     property="data_prohibition",
 *     title="Prohibition of data changes",
 *     example="2020-09-18 11:42:35",
 *     type="timestamp"
 * )
 * @OA\Property(
 *     property="is_kits",
 *     title="Use Kits",
 *     example=true,
 *     type="boolean"
 * )
 * @OA\Property(
 *     property="is_labels_price_tags",
 *     title="Printing labels and price tags",
 *     example=false,
 *     type="boolean"
 * )
 * @OA\Property(
 *     property="is_residue_control",
 *     title="Use Kits",
 *     example=true,
 *     type="boolean"
 * )
 * @OA\Property(
 *     property="is_сhange_history",
 *     title="Use Kits",
 *     example=false,
 *     type="boolean"
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

class Company extends Model
{
    protected $table = 'companies';

    protected $fillable = [
        'uniq_id',
        'owner_id',
        'language_interface_id',
        'form_payments_id',
        'terms_payment_id',
        'name',
        'domain',
        'currency_id',
        'archive_cleare',
        'data_prohibition',
        'is_kits',
        'is_labels_price_tags',
        'is_residue_control',
        'is_сhange_history',
        'db_database',
        'db_username',
        'db_password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'uniq_id', 'owner_id', 'db_database', 'db_username', 'db_password'
    ];

    protected $appends = ['main_domain', 'user_main_domain'];

    protected $casts = [
        'is_kits'=>'boolean',
        'is_labels_price_tags'=>'boolean',
        'is_residue_control'=>'boolean',
        'is_сhange_history'=>'boolean'
    ];

    public function getMainDomainAttribute()
    {
        return config('app.main_domain');
    }

    public function getUserMainDomainAttribute()
    {
        return str_replace('gateway.', '' , $this->domain);
    }
}
