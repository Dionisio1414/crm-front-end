<?php

namespace App\Directories\Models\Organizations;

use Illuminate\Database\Eloquent\Model;

class DirectoryOrganizationsDetailsContactAdress extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'organization_detail_id',
        'phone',
        'email',
        'country_id',
        'region_id',
        'city_id',
        'city',
        'number_house'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'organization_detail_id'];
}
