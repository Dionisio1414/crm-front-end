<?php

namespace App\Directories\Models\Employees;

use Illuminate\Database\Eloquent\Model;

class DirectoryEmployeesDetailsContact extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'mobile_phone',
        'work_phone',
        'internal_phone',
        'email',
        'skype',
        'zoom',
        'residence_country_id',
        'residence_region_id',
        'residence_city_id',
        'residence_street',
        'residence_number_house',
        'is_equal_residence_registration',
        'registration_country_id',
        'registration_region_id',
        'registration_city_id',
        'registration_street',
        'registration_number_house',
        'directory_employee_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id', 'directory_employee_id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_equal_residence_registration' => 'boolean'
    ];

    public $timestamps = false;
}
