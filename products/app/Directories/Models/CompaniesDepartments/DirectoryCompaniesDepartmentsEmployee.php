<?php

namespace App\Directories\Models\CompaniesDepartments;

use Illuminate\Database\Eloquent\Model;

class DirectoryCompaniesDepartmentsEmployee extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'company_department_id',
        'employee_id',
        'is_leader',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id', 'created_at', 'updated_at', 'company_department_id'];

    protected $casts = [
        'is_leader'=>'boolean'
    ];
}
