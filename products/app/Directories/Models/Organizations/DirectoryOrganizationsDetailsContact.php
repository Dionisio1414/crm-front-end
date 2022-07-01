<?php

namespace App\Directories\Models\Organizations;

use Illuminate\Database\Eloquent\Model;

class DirectoryOrganizationsDetailsContact extends Model
{
    protected $table = 'directory_organizations_details_contact';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'organization_id',
        'legal_country_id',
        'legal_region_id',
        'legal_city_id',
        'legal_city',
        'legal_number_house',
        'is_legal_equal_actual',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'organization_id'];

    protected $casts = [
        'is_legal_equal_actual'=>'boolean'
    ];

    protected $with = ['values'];

    public function values()
    {
        return $this->hasMany(DirectoryOrganizationsDetailsContactAdress::class, 'organization_detail_id', 'id');
    }
}
