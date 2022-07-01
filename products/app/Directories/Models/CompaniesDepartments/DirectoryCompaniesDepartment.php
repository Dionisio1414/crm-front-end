<?php

namespace App\Directories\Models\CompaniesDepartments;

use Illuminate\Database\Eloquent\Model;
use App\Core\Helpers\TitleJson;

class DirectoryCompaniesDepartment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'title', 'is_default', 'code', 'archive', 'order'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'archive'];

    protected $casts = [
        'title'=>'json',
        'is_default'=>'boolean',
        'archive'=>'boolean'
    ];

    protected $with = [
        'employees'
    ];

    public function employees()
    {
        return $this->hasMany(DirectoryCompaniesDepartmentsEmployee::class, 'company_department_id', 'id');
    }
}
