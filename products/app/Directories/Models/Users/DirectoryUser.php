<?php

namespace App\Directories\Models\Users;

use App\Directories\Models\Employees\DirectoryEmployee;
use Illuminate\Database\Eloquent\Model;
use App\Directories\Models\Positions\DirectoryPositionsDetail;
use App\Directories\Models\CompaniesDepartments\DirectoryCompaniesDepartment;

class DirectoryUser extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'directory_employee_id',
        'gateway_user_id',
        'is_invited',
        'archive'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'gateway_user_id', 'is_invited', 'archive', 'directory_employee_id'];

    protected $with = ['cells','employee'];

    protected $casts = [
        'is_invited' => 'boolean',
        'archive'    => 'boolean'
    ];

    public function cells()
    {
        return $this->hasMany(DirectoryUsersDetail::class, 'directory_user_id', 'id');
    }

    public function employee()
    {
        return $this->hasOne(DirectoryEmployee::class, 'id', 'directory_employee_id')->without(['cells', 'documents']);
    }
}
