<?php

namespace App\Directories\Models\Organizations;

use Illuminate\Database\Eloquent\Model;

class DirectoryOrganizationsDetailsMain extends Model
{
    protected $table = 'directory_organizations_details_main';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'organization_id',
        'organization_type_id',
        'title',
        'full_title',
        'first_name',
        'last_name',
        'middle_name',
        'inn',
        'passport_serial',
        'passport_serial_number',
        'passport_issued',
        'passport_issued_date',
        'citizenship',
        'sex_id',
        'date_of_birth',
        'cashbox_id',
        'checking_account_id',
        'price_id',
        'edrpou',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'organization_id'];

    protected $casts = [];
}
