<?php

namespace App\Directories\Models\Employees;

use Illuminate\Database\Eloquent\Model;

class DirectoryEmployeesDetailsMain extends Model
{
    protected $table = 'directory_employees_details_main';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'salary',
        'type_price_id',
        'inn',
        'directory_employee_id',
        'is_not_active',
        'date_receipt',
        'date_dismissal'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id', 'directory_employee_id'];

    protected $casts = [
        'is_not_active'=>'boolean'
    ];

    public $timestamps = false;
}
