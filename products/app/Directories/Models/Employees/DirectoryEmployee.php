<?php

namespace App\Directories\Models\Employees;

use App\Core\Model\Yesno;
use App\Directories\Models\CompaniesDepartments\DirectoryCompaniesDepartment;
use App\Directories\Models\Positions\DirectoryPositionsDetail;
use App\Directories\Models\Users\DirectoryUser;
use App\FileManager\Models\FileManager;
use Illuminate\Database\Eloquent\Model;

class DirectoryEmployee extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'thumbnail_id',
        'first_name',
        'last_name',
        'middle_name',
        'date_of_birth',
        'sex_id',
        'company_department_id',
        'position_id',
        'archive',
        'is_user',
        'directory_user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'archive', 'archive', 'directory_user_id'];

    protected $with = ['cells', 'main', 'contact', 'documents', 'thumbnail'];

    protected $appends = ['full_name'];

    protected $casts = [
        'archive'=>'boolean',
        'is_user'=>'boolean'
    ];

    public function cells()
    {
        return $this->hasMany(DirectoryEmployeesDetail::class, 'directory_employee_id', 'id');
    }

    public function main()
    {
        return $this->hasOne(DirectoryEmployeesDetailsMain::class, 'directory_employee_id', 'id');
    }

    public function contact()
    {
        return $this->hasOne(DirectoryEmployeesDetailsContact::class, 'directory_employee_id', 'id');
    }

    public function documents()
    {
        return $this->hasMany(DirectoryEmployeesDetailsDocument::class, 'directory_employee_id', 'id')->where('archive', Yesno::NO);
    }

    public function position()
    {
        return $this->hasOne(DirectoryPositionsDetail::class, 'position_id', 'position_id');
    }

    public function department()
    {
        return $this->hasOne(DirectoryCompaniesDepartment::class, 'id', 'company_department_id');
    }

    public function getFullNameAttribute()
    {
        return $this->last_name . " " . $this->first_name . " " . $this->middle_name;
    }

    public function thumbnail()
    {
        return $this->hasOne(FileManager::class, 'id', 'thumbnail_id');
    }

    public function user()
    {
        return $this->hasOne(DirectoryUser::class, 'id', 'directory_user_id');
    }
}
